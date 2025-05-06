<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Document;
use App\Models\Comprobante;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    const STATUS_PRESTADO = 'Prestado';
    const STATUS_DEVUELTO = 'Devuelto';
    // Mostrar formulario de préstamo
    public function createLoan($book_id)
    {
        $book = Book::with('comprobantes')->findOrFail($book_id);

        // Si es un libro de comprobantes
        if($book->isComprobanteTipo()) {
            $comprobantes_disponibles = $book->comprobantes()
                                           ->where('estado', 'activo')
                                           ->get();

            return view('admin.documents.create_comprobantess',
                compact('book', 'comprobantes_disponibles'));
        }

        // Si es un libro normal, usar la vista existente
        if ($book->estado === Book::ESTADO_PRESTADO) {
            return redirect()->back()->with('error', 'Esta carpeta ya está prestada');
        }

        return view('admin.documents.create', compact('book'));
    }

    // Guardar el préstamo
    public function storeLoan(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
                'applicant_name' => 'required|string|max:255',
                'tipo_prestamo' => 'required|in:completo,parcial',
                'comprobantes' => 'required_if:tipo_prestamo,parcial|array',
                'comprobantes.*' => 'exists:comprobantes,id',
                'fecha_devolucion' => 'required|date|after_or_equal:today',
                'N_hojas' => 'nullable|string',
                'N_carpeta' => 'nullable|string',
                'description' => 'nullable|string'
            ]);

            $book = Book::find($request->book_id);

            $document = Document::create([
                'book_id' => $request->book_id,
                'applicant_name' => $request->applicant_name,
                'N_hojas' => $request->N_hojas,
                'N_carpeta' => $request->N_carpeta,
                'description' => $request->description,
                'status' => self::STATUS_PRESTADO,
                'fecha_prestamo' => now(),
                'fecha_devolucion' => Carbon::parse($request->fecha_devolucion)->setTime(23, 59, 59),
                'category_id' => $book->category_id,
                'user_id' => auth()->id()
            ]);

            if ($request->tipo_prestamo === 'completo') {
                // Préstamo de carpeta completa
                Book::where('id', $request->book_id)
                    ->update(['estado' => Book::ESTADO_PRESTADO]);

                // Marcar todos los comprobantes como inactivos
                $book->comprobantes()->update(['estado' => 'inactivo']);
            } else {
                // Préstamo de comprobantes individuales
                foreach ($request->comprobantes as $comprobante_id) {
                    $document->comprobantes()->attach($comprobante_id, [
                        'fecha_prestamo' => now()->format('Y-m-d H:i:s'),
                        'estado' => 'prestado'
                    ]);

                    Comprobante::where('id', $comprobante_id)
                              ->update(['estado' => 'inactivo']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Préstamo registrado exitosamente',
                'redirect' => route('document.loans.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en préstamo: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el préstamo: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Muestra los detalles de un préstamo
     *
     * @param int $document
     * @return \Illuminate\View\View
     */
    public function showLoan($document)
    {
        try {
            // Log para depuración
            Log::info('Accediendo a showLoan para documento ID: ' . $document);

            // Obtener el documento con sus relaciones principales
            $document = Document::with(['book', 'book.category', 'user', 'category'])
                ->findOrFail($document);

            // Cargar comprobantes después para evitar problemas si no existen
            $document->load('comprobantes');

            // Calcular estadísticas (con validaciones para evitar errores)
            $estadisticas = [
                'total' => $document->comprobantes ? $document->comprobantes->count() : 0,
                'prestados' => $document->comprobantes ? $document->comprobantesPrestados()->count() : 0,
                'devueltos' => $document->comprobantes ? $document->comprobantesDevueltos()->count() : 0,
                'vencidos' => $document->comprobantes ? $document->comprobantesPrestados()
                    ->wherePivot('fecha_devolucion', '<', now())
                    ->count() : 0
            ];

            Log::info('Documento cargado con éxito', [
                'document_id' => $document->id,
                'tiene_book' => $document->book ? 'sí' : 'no',
                'tiene_user' => $document->user ? 'sí' : 'no',
                'comprobantes_count' => $document->comprobantes->count(),
                'estadisticas' => $estadisticas
            ]);

            // Renderizar vista con los datos
            return view('admin.documents.show', compact('document', 'estadisticas'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Documento no encontrado: ' . $document);

            return redirect()->route('document.loans.index')
                ->with('error', 'El préstamo solicitado no existe.');

        } catch (\Exception $e) {
            Log::error('Error en showLoan: ' . $e->getMessage() . ' en línea ' . $e->getLine() . ' de ' . $e->getFile());

            return redirect()->route('document.loans.index')
                ->with('error', 'Ocurrió un error al mostrar el préstamo: ' . $e->getMessage());
        }
    }

    // Actualizar estado del préstamo
    public function updateLoan(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:' . self::STATUS_PRESTADO . ',' . self::STATUS_DEVUELTO
        ]);

        try {
            if ($request->status === self::STATUS_DEVUELTO) {
                $document->update([
                    'status' => self::STATUS_DEVUELTO,
                    'fecha_devolucion' => Carbon::now()
                ]);

                // Actualizar estado del libro
                $document->book->update(['estado' => Book::ESTADO_NO_PRESTADO]);

                // Si hay comprobantes prestados, marcarlos como devueltos
                $document->comprobantes()
                        ->wherePivot('estado', 'prestado')
                        ->each(function($comprobante) use ($document) {
                            $document->comprobantes()->updateExistingPivot($comprobante->id, [
                                'estado' => 'devuelto',
                                'fecha_devolucion' => Carbon::now()->format('Y-m-d H:i:s')
                            ]);
                            $comprobante->update(['estado' => 'activo']);
                        });

                $message = 'Carpeta marcada como devuelta';
            } else {
                $document->update(['status' => $request->status]);
                $message = 'Estado actualizado correctamente';
            }

            // Verificar si la solicitud espera respuesta JSON (desde AJAX)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            // Si es una solicitud tradicional, redirigir
            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error al actualizar préstamo: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar el estado del préstamo'
                ], 500);
            }

            return redirect()->back()->with('error', 'No se pudo actualizar el estado del préstamo');
        }
    }

    // Listar todos los préstamos
    public function index()
    {
        try {
            Log::info('Iniciando DocumentController@index');

            // Primero verificar si podemos obtener documentos sin relaciones
            $documents = Document::orderBy('created_at', 'desc')->get();
            Log::info('Documentos base obtenidos: ' . $documents->count());

            // Ahora intentar cargar cada relación por separado
            try {
                $documentsWithBooks = Document::with('book')->first();
                Log::info('Relación con books OK');
            } catch (\Exception $e) {
                Log::error('Error en relación con books: ' . $e->getMessage());
            }

            try {
                $documentsWithUsers = Document::with('user')->first();
                Log::info('Relación con users OK');
            } catch (\Exception $e) {
                Log::error('Error en relación con users: ' . $e->getMessage());
            }

            try {
                $documentsWithComprobantes = Document::with('comprobantes')->first();
                Log::info('Relación con comprobantes OK');
            } catch (\Exception $e) {
                Log::error('Error en relación con comprobantes: ' . $e->getMessage());
            }

            // Si todo está bien hasta aquí, intentar con la carga completa
            $documents = Document::with(['book', 'user', 'comprobantes'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('admin.documents.index', compact('documents'));
        } catch (\Exception $e) {
            Log::error('Error completo en DocumentController@index: ' . $e->getMessage());
            Log::error('Línea: ' . $e->getLine() . ' en archivo: ' . $e->getFile());

            return back()->with('error', 'Ocurrió un error al cargar los préstamos. ' . $e->getMessage());
        }
    }

    public function getComprobantes(Document $document, Request $request)
    {
        try {
            Log::info('Obteniendo comprobantes para documento: ' . $document->id);
            Log::info('Formato solicitado: ' . ($request->format ?? 'no especificado'));

            // Cargar el documento con sus relaciones
            $document->load(['book', 'comprobantes', 'user']);

            $comprobantes = $document->comprobantes->map(function($comprobante) {
                // Convertir y formatear las fechas manualmente
                $fecha_prestamo_obj = null;
                $fecha_prestamo_formateada = null;
                if (!empty($comprobante->pivot->fecha_prestamo)) {
                    try {
                        $fecha_prestamo_obj = Carbon::parse($comprobante->pivot->fecha_prestamo);
                        $fecha_prestamo_formateada = $fecha_prestamo_obj->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        Log::error('Error al parsear fecha_prestamo: ' . $e->getMessage());
                    }
                }

                $fecha_devolucion_obj = null;
                $fecha_devolucion_formateada = null;
                if (!empty($comprobante->pivot->fecha_devolucion)) {
                    try {
                        $fecha_devolucion_obj = Carbon::parse($comprobante->pivot->fecha_devolucion);
                        $fecha_devolucion_formateada = $fecha_devolucion_obj->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        Log::error('Error al parsear fecha_devolucion: ' . $e->getMessage());
                    }
                }

                // Log para depuración
                Log::info('Fecha préstamo original: ' . $comprobante->pivot->fecha_prestamo);
                Log::info('Fecha préstamo formateada: ' . $fecha_prestamo_formateada);

                return [
                    'id' => $comprobante->id,
                    'numero_comprobante' => $comprobante->numero_comprobante,
                    'n_hojas' => $comprobante->n_hojas,
                    'descripcion' => $comprobante->descripcion ?? 'Sin descripción',
                    'pdf_file' => $comprobante->pdf_file,
                    'estado' => $comprobante->pivot->estado ?? 'prestado',
                    'fecha_prestamo' => $fecha_prestamo_formateada,
                    'fecha_devolucion' => $fecha_devolucion_formateada
                ];
            });

            // Estadísticas útiles
            $stats = [
                'total' => $comprobantes->count(),
                'prestados' => $comprobantes->where('estado', 'prestado')->count(),
                'devueltos' => $comprobantes->where('estado', 'devuelto')->count(),
                'vencidos' => $comprobantes->where('estado', 'prestado')
                    ->filter(function($comp) use ($document) {
                        return Carbon::parse($document->fecha_devolucion)->isPast();
                    })->count()
            ];

            Log::info('Comprobantes encontrados: ' . $comprobantes->count());

            // Verificar si se solicita formato PDF de manera explícita
            if ($request->has('format') && $request->format == 'pdf') {
                Log::info('Generando PDF para documento: ' . $document->id);

                $data = [
                    'document' => $document,
                    'comprobantes' => $comprobantes,
                    'stats' => $stats,
                    'book' => [
                        'title' => $document->book->title,
                        'code' => $document->book->N_codigo,
                        'year' => $document->book->year,
                        'tomo' => $document->book->tomo,
                        'ubicacion' => $document->book->ubicacion ?? 'No especificada',
                        'image' => $document->book->book_img ?? null
                    ],
                    'prestamo' => [
                        'solicitante' => $document->applicant_name ?? 'No especificado',
                        'estado' => $document->status ?? 'No especificado',
                        'fecha_prestamo' => $document->fecha_prestamo ?
                            'Fecha: ' . date('d/m/Y', strtotime($document->fecha_prestamo)) . ' - Hora: ' . date('H:i:s', strtotime($document->fecha_prestamo)) :
                            'Fecha: ' . date('d/m/Y') . ' - Hora: ' . date('H:i:s'),
                        'fecha_devolucion' => $document->fecha_devolucion ?
                            'Fecha: ' . date('d/m/Y', strtotime($document->fecha_devolucion)) . ' - Hora: ' . date('H:i:s', strtotime($document->fecha_devolucion)) :
                            null,
                        'administrador' => $document->user_id ? ($document->user->name ?? 'Usuario #'.$document->user_id) :
                            (auth()->check() ? auth()->user()->name : 'Sistema')
                    ]
                ];

                $pdf = Pdf::loadView('admin.documents.comprobantesPDF', $data);
                return $pdf->stream('comprobantes-documento-'.$document->id.'.pdf');
            }

            // Si no es PDF, devolver JSON como estaba antes
            return response()->json([
                'success' => true,
                'document_id' => $document->id,
                'comprobantes' => $comprobantes,
                'stats' => $stats,
                'book' => [
                    'title' => $document->book->title,
                    'code' => $document->book->N_codigo,
                    'year' => $document->book->year,
                    'tomo' => $document->book->tomo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener comprobantes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los comprobantes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function returnComprobante(Document $document, Comprobante $comprobante)
    {
        DB::beginTransaction();
        try {
            // Actualizar el estado del comprobante en la tabla pivot
            $document->comprobantes()->updateExistingPivot($comprobante->id, [
                'estado' => 'devuelto',
                'fecha_devolucion' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            // Activar el comprobante
            $comprobante->update(['estado' => 'activo']);

            // Verificar si todos los comprobantes han sido devueltos
            $pendingComprobantes = $document->comprobantes()
                                          ->wherePivot('estado', 'prestado')
                                          ->count();

            $allReturned = $pendingComprobantes === 0;

            // Si todos están devueltos, actualizar el documento
            if ($allReturned) {
                $document->update([
                    'status' => self::STATUS_DEVUELTO,
                    'fecha_devolucion' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'allReturned' => $allReturned,
                'pendingCount' => $pendingComprobantes,
                'message' => 'Comprobante devuelto correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al devolver comprobante: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al devolver el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener detalles de un comprobante específico
     */
    public function getComprobanteDetails(Comprobante $comprobante)
    {
        try {
            // Obtener el comprobante con toda su información
            $comprobante->load(['documents' => function($query) {
                $query->withPivot('estado', 'fecha_prestamo', 'fecha_devolucion');
            }, 'book']);

            // Encontrar el documento/préstamo actual
            $prestamoActual = $comprobante->documents()
                ->wherePivot('estado', 'prestado')
                ->first();

            return response()->json([
                'success' => true,
                'comprobante' => $comprobante,
                'book' => $comprobante->book,
                'prestamo' => $prestamoActual ? [
                    'id' => $prestamoActual->id,
                    'applicant_name' => $prestamoActual->applicant_name,
                    'fecha_prestamo' => $prestamoActual->pivot->fecha_prestamo,
                    'fecha_devolucion_esperada' => $prestamoActual->fecha_devolucion,
                    'estado' => $prestamoActual->pivot->estado,
                    'dias_restantes' => Carbon::now()->diffInDays(
                        Carbon::parse($prestamoActual->fecha_devolucion), false
                    )
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalles del comprobante: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista para gestionar los comprobantes de un préstamo
     */
    public function manageComprobantes(Document $document)
    {
        try {
            // Cargar el documento con sus relaciones
            $document->load(['book', 'book.category', 'comprobantes']);

            // Verificar que sea un préstamo de comprobantes
            if (!$document->book->isComprobanteTipo()) {
                return redirect()->route('document.loan.show', $document->id)
                    ->with('error', 'Este préstamo no es de tipo comprobante');
            }

            // Calcular estadísticas
            $estadisticas = [
                'total' => $document->comprobantes->count(),
                'prestados' => $document->comprobantesPrestados()->count(),
                'devueltos' => $document->comprobantesDevueltos()->count(),
                'vencidos' => $document->comprobantesPrestados()
                    ->wherePivot('fecha_devolucion', '<', now())
                    ->count()
            ];

            return view('admin.documents.comprobantes', compact('document', 'estadisticas'));

        } catch (\Exception $e) {
            Log::error('Error en manageComprobantes: ' . $e->getMessage());
            return redirect()->route('document.loans.index')
                ->with('error', 'Error al cargar la gestión de comprobantes: ' . $e->getMessage());
        }
    }

    /**
     * Genera un PDF para un préstamo general (no relacionado con comprobantes)
     *
     * @param  Document  $document
     * @return \Illuminate\Http\Response
     */
    public function generarPDFGeneral(Document $document)
    {
        try {
            Log::info('Generando PDF para préstamo general: ' . $document->id);
            Log::info('Datos del documento: N_hojas=' . $document->N_hojas . ', N_carpeta=' . $document->N_carpeta);

            // Cargar el documento con sus relaciones
            $document->load(['book', 'category', 'user']);

            // Log para debugging del usuario
            Log::info('Usuario del documento:', ['user_id' => $document->user_id, 'tiene_user' => $document->user ? 'sí' : 'no', 'nombre_usuario' => $document->user ? $document->user->name : 'No disponible']);

            // Verificar si es el tipo de categoría adecuado (general, no comprobante)
            if ($document->category && $document->category->tipo === 'comprobante') {
                Log::warning('Se solicitó PDF general para un documento de tipo comprobante. ID: ' . $document->id);
                return redirect()->back()->with('error', 'Este documento es de tipo comprobante y requiere un reporte específico');
            }

            // Verificar que el documento tenga libro asociado
            if (!$document->book) {
                Log::error('Documento sin libro asociado. ID: ' . $document->id);
                return redirect()->back()->with('error', 'El documento no tiene una carpeta asociada');
            }

            // Preparar datos para la vista
            $data = [
                'document' => [
                    'id' => $document->id,
                    'N_carpeta' => $document->N_carpeta,
                    'N_hojas' => $document->N_hojas,
                    'descripcion' => $document->description ?? 'Sin descripción',
                    'notas' => $document->notes ?? null,
                    'fecha_creacion' => $document->created_at ? $document->created_at->format('d/m/Y H:i:s') : 'No registrada'
                ],
                'book' => [
                    'title' => $document->book->title ?? 'Sin título',
                    'code' => $document->book->N_codigo ?? 'Sin código',
                    'year' => $document->book->year ?? 'No especificado',
                    'tomo' => $document->book->tomo ?? 'No especificado',
                    'N_hojas' => $document->book->N_hojas ?? 'No especificado',
                    'ubicacion' => $document->book->ubicacion ?? 'No especificada',
                    'ambiente' => $document->book->ambiente ?? 'No especificado',
                    'bandeja' => $document->book->bandeja ?? 'No especificada',
                    'categoria' => $document->book->category ? $document->book->category->cat_title : 'No especificada',
                    'image' => $document->book->book_img ?? null
                ],
                'prestamo' => [
                    'solicitante' => $document->applicant_name ?? 'No especificado',
                    'estado' => $document->status ?? 'No especificado',
                    'fecha_prestamo' => $document->fecha_prestamo ?
                        'Fecha: ' . $document->fecha_prestamo->format('d/m/Y') . ' - Hora: ' . $document->fecha_prestamo->format('H:i:s') :
                        now()->format('d/m/Y H:i:s'),
                    'fecha_devolucion' => $document->fecha_devolucion ?
                        'Fecha: ' . $document->fecha_devolucion->format('d/m/Y') . ' - Hora: ' . $document->fecha_devolucion->format('H:i:s') :
                        null,
                    'descripcion' => $document->description ?? 'Sin descripción',
                    'dias_prestamo' => $document->fecha_prestamo && $document->fecha_devolucion
                        ? $document->fecha_prestamo->diffInDays($document->fecha_devolucion)
                        : ($document->fecha_prestamo ? $document->fecha_prestamo->diffInDays(now()) : 0),
                    'administrador' => $document->user_id ? ($document->user->name ?? 'Usuario #'.$document->user_id) :
                        (auth()->check() ? auth()->user()->name : 'Sistema')
                ],
                'historial' => [] // Se podría implementar un historial de acciones para el documento
            ];

            // Cargar la vista PDF con manejo de errores
            try {
                $pdf = Pdf::loadView('admin.documents.generalPDF', $data);

                // Generar el nombre del archivo
                $filename = 'prestamo-general-' . $document->id . '-' . now()->format('dmY') . '.pdf';

                // Retornar el PDF para descarga o visualización
                return $pdf->stream($filename);
            } catch (\Exception $e) {
                Log::error('Error al generar la vista PDF: ' . $e->getMessage());
                return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Error al generar PDF general: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function getComprobantesJSON(Request $request, Document $document)
    {
        try {
            // Cargar relaciones necesarias para el documento
            $document->load(['book', 'comprobantes', 'user']);

            // ... rest of the method ...
        } catch (\Exception $e) {
            // ... handle exception ...
        }
    }

}

