<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comprobante;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\pdf as DomPDF;

class ComprobanteController extends Controller
{
    public function index(Book $book)
    {
        $comprobantes = $book->comprobantes()->paginate(10);
        return view('admin.comprobantes.index', compact('book', 'comprobantes'));
    }

    public function create(Book $book, Request $request)
    {
        // Tomar datos pasados por URL
        $inicio = $request->query('inicio');
        $fin = $request->query('fin');

        return view('admin.comprobantes.create', compact('book', 'inicio', 'fin'));
    }

    public function store(Request $request, Book $book)
    {
        $request->validate([
            'comprobante_inicio' => 'required|integer|min:1',
            'comprobante_fin' => 'required|integer|gte:comprobante_inicio',
            'n_hojas' => 'nullable|integer|min:0',
            'codigo_personalizado' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric|min:0',
            'pdf_file' => 'nullable|mimes:pdf|max:50000',
            'descripcion' => 'nullable|string|max:1000'
        ]);

        $inicio = $request->comprobante_inicio;
        $fin = $request->comprobante_fin;
        $n_hojas = $request->n_hojas ?? 0;

        $resultado = $this->crearRangoComprobantes(
            $book->id,
            $inicio,
            $fin,
            $n_hojas,
            $request->descripcion,
            $request->costo
        );

        if (!$resultado['success']) {
            return redirect()->back()->with('error', $resultado['message']);
        }

        // Manejar datos adicionales solo para el primer comprobante si existe
        if ($resultado['success'] && ($request->hasFile('pdf_file') || $request->codigo_personalizado)) {
            $primerComprobante = $book->comprobantes()
                                     ->where('numero_comprobante', $inicio)
                                     ->first();

            if ($primerComprobante) {
                // Manejar PDF
                if ($request->hasFile('pdf_file')) {
                    $pdf = $request->file('pdf_file');
                    $pdfName = time() . '_comprobante_' . $inicio . '.pdf';
                    $pdf->move(public_path('comprobantes'), $pdfName);
                    $primerComprobante->pdf_file = $pdfName;
                }

                // Manejar código personalizado
                if ($request->codigo_personalizado) {
                    $primerComprobante->codigo_personalizado = $request->codigo_personalizado;
                }

                $primerComprobante->save();
            }
        }

        return redirect()->route('books.comprobantes.index', $book)
                        ->with('message', $resultado['message']);
    }

    public function show(Book $book, Comprobante $comprobante)
    {
        // Verificar que el comprobante pertenezca al libro
        if ($comprobante->book_id !== $book->id) {
            return response()->json(['error' => 'El comprobante no pertenece a esta carpeta'], 403);
        }

        // Cargar relaciones necesarias
        $comprobante->load('book', 'loans');

        // Si es una solicitud AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $comprobante->id,
                'numero' => $comprobante->numero,
                'descripcion' => $comprobante->descripcion,
                'estado' => $comprobante->estado,
                'prestado' => $comprobante->isPrestado(),
                'fecha_creacion' => $comprobante->created_at->format('d/m/Y H:i'),
                'tiene_pdf' => !empty($comprobante->pdf_path),
                'pdf_url' => $comprobante->pdf_path ? route('books.comprobantes.pdf', [$book->id, $comprobante->id]) : null,
                // Otros datos que necesites
            ]);
        }

        // Si no es AJAX, mostrar la vista de detalles
        return view('admin.comprobantes.show', compact('book', 'comprobante'));
    }

    public function edit(Book $book, Comprobante $comprobante)
    {
        return view('admin.comprobantes.edit', compact('book', 'comprobante'));
    }

    public function update(Request $request, Book $book, Comprobante $comprobante)
    {
        $request->validate([
            'n_hojas' => 'required|integer|min:0',
            'estado' => 'required|in:activo,inactivo',
            'codigo_personalizado' => 'nullable|string|max:255',
            'costo' => 'nullable|numeric|min:0',
            'pdf_file' => 'nullable|mimes:pdf|max:50000',
            'descripcion' => 'nullable|string|max:1000'
        ]);

        $comprobante->n_hojas = $request->n_hojas;
        $comprobante->estado = $request->estado;
        $comprobante->descripcion = $request->descripcion;
        $comprobante->codigo_personalizado = $request->codigo_personalizado;
        $comprobante->costo = $request->costo;

        if ($request->hasFile('pdf_file')) {
            // Eliminar PDF anterior si existe
            if ($comprobante->pdf_file) {
                $oldPdfPath = public_path('comprobantes/' . $comprobante->pdf_file);
                if (file_exists($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }

            // Guardar nuevo PDF
            $pdf = $request->file('pdf_file');
            $pdfName = time() . '_comprobante_' . $comprobante->numero_comprobante . '.pdf';
            $pdf->move(public_path('comprobantes'), $pdfName);
            $comprobante->pdf_file = $pdfName;
        }

        $comprobante->save();

        return redirect()->route('books.comprobantes.index', $book)
                        ->with('message', 'Comprobante actualizado correctamente');
    }

    public function destroy(Book $book, Comprobante $comprobante)
    {
        // Verificar si el comprobante está prestado
        $isPrestado = DB::table('document_comprobante')
                     ->where('comprobante_id', $comprobante->id)
                     ->where('estado', 'prestado')
                     ->exists();

        if ($isPrestado) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar un comprobante que está prestado.');
        }

        // Eliminar PDF si existe
        if ($comprobante->pdf_file) {
            $pdfPath = public_path('comprobantes/' . $comprobante->pdf_file);
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        $comprobante->delete();

        return redirect()->route('books.comprobantes.index', $book->id)
                        ->with('success', 'Comprobante eliminado correctamente');
    }

    public function showComprobantes($book_id)
    {
        $book = Book::with('comprobantes')->findOrFail($book_id);
        return view('admin.comprobantes.list', compact('book'));
    }

    public function getComprobantes(Document $document)
    {
        try {
            Log::info('Obteniendo comprobantes para documento: ' . $document->id);

            // Cargar el documento con sus relaciones
            $document->load(['book', 'comprobantes']);

            $comprobantes = $document->comprobantes->map(function($comprobante) {
                return [
                    'id' => $comprobante->id,
                    'numero_comprobante' => $comprobante->numero_comprobante,
                    'n_hojas' => $comprobante->n_hojas,
                    'descripcion' => $comprobante->descripcion ?? 'Sin descripción',
                    'pdf_file' => $comprobante->pdf_file,
                    'estado' => $comprobante->pivot->estado ?? 'prestado',
                    'fecha_prestamo' => $comprobante->pivot->fecha_prestamo,
                    'fecha_devolucion' => $comprobante->pivot->fecha_devolucion
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

            return response()->json([
                'success' => true,
                'document' => $document,
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

    /**
     * Obtener detalles de un comprobante específico
     */
    public function getComprobanteDetails(Comprobante $comprobante)
    {
        try {
            // Obtener el comprobante con toda su información
            $comprobante->load(['documents' => function($query) {
                $query->withPivot('estado', 'fecha_prestamo', 'fecha_devolucion');
            }]);

            // Encontrar el documento/préstamo actual
            $prestamoActual = $comprobante->documents()
                ->wherePivot('estado', 'prestado')
                ->first();

            return response()->json([
                'success' => true,
                'comprobante' => $comprobante,
                'prestamo' => $prestamoActual ? [
                    'id' => $prestamoActual->id,
                    'fecha_prestamo' => $prestamoActual->pivot->fecha_prestamo,
                    'estado' => $prestamoActual->pivot->estado
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

    public function returnComprobante(Document $document, Comprobante $comprobante)
    {
        DB::beginTransaction();
        try {
            // Actualizar el estado del comprobante en la tabla pivot
            $document->comprobantes()->updateExistingPivot($comprobante->id, [
                'estado' => 'devuelto',
                'fecha_devolucion' => now()
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
                    'status' => 'Devuelto',
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
     * Crear un rango de comprobantes para un libro
     */
    public function crearRangoComprobantes($bookId, $inicio, $fin, $n_hojas = 0, $descripcion = null, $costo = null)
    {
        try {
            DB::beginTransaction();

            $book = Book::findOrFail($bookId);

            // Verificar si ya existen comprobantes en ese rango
            $existentes = $book->comprobantes()
                             ->whereBetween('numero_comprobante', [$inicio, $fin])
                             ->pluck('numero_comprobante')
                             ->toArray();

            if (count($existentes) > 0) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Ya existen comprobantes en ese rango: ' . implode(', ', $existentes)
                ];
            }

            // Crear comprobantes
            for ($i = $inicio; $i <= $fin; $i++) {
                $book->comprobantes()->create([
                    'numero_comprobante' => $i,
                    'n_hojas' => $n_hojas,
                    'estado' => 'activo',
                    'descripcion' => $descripcion,
                    'costo' => $costo
                ]);
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Se crearon ' . ($fin - $inicio + 1) . ' comprobantes correctamente'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear rango de comprobantes: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al crear comprobantes: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genera un reporte PDF de los comprobantes de una carpeta
     */
    public function generateReport(Book $book)
    {
        try {
            Log::info('Iniciando generación de reporte para libro ID: ' . $book->id);

            // Verificar que el libro sea tipo comprobante
            if (!$book->isComprobanteTipo()) {
                Log::warning('Libro no es tipo comprobante', ['book_id' => $book->id]);
                return back()->with('error', 'Este libro no es de tipo comprobante');
            }

            Log::info('Carpeta verificada como tipo comprobante, cargando datos...');

            // Calcular estadísticas
            $stats = [
                'total' => $book->comprobantes->count(),
                'disponibles' => $book->comprobantes->where('estado', 'activo')->count(),
                'prestados' => $book->comprobantes->where('estado', 'prestado')->count(),
                'total_hojas' => $book->comprobantes->sum('n_hojas'),
                'total_costo' => $book->comprobantes->sum('costo')
            ];

            Log::info('Estadísticas calculadas', $stats);

            // Verificar que existan comprobantes
            if ($stats['total'] === 0) {
                Log::warning('No hay comprobantes para generar el reporte', ['book_id' => $book->id]);
                return back()->with('error', 'No hay comprobantes para generar el reporte');
            }

            // Preparar imágenes
            $escudoPath = public_path('images/escudo.png');
            $banderaPath = public_path('images/bandera.png');

            $escudoBase64 = null;
            $banderaBase64 = null;

            // Convertir a base64 si existen los archivos
            if (file_exists($escudoPath)) {
                $escudoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($escudoPath));
            }

            if (file_exists($banderaPath)) {
                $banderaBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($banderaPath));
            }

            // Ordenar comprobantes por número
            $comprobantes = $book->comprobantes->sortBy('numero_comprobante');

            // Generar el PDF
            Log::info('Generando vista PDF');
            $pdf = DomPDF::loadView('admin.comprobantes.report', [
                'book' => $book,
                'comprobantes' => $comprobantes,
                'stats' => $stats,
                'fecha_generacion' => now()->format('d/m/Y H:i:s'),
                'escudoBase64' => $escudoBase64,
                'banderaBase64' => $banderaBase64
            ]);

            // Configuración básica - cambiar a horizontal y configurar opciones
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
                'chroot' => public_path(),
                'enable_remote' => true,
                'log_output_file' => storage_path('logs/dompdf.log'),
                'debugKeepTemp' => true
            ]);

            // Nombre del archivo
            $filename = 'reporte_comprobantes_' . $book->N_codigo . '_' . now()->format('dmY_His') . '.pdf';
            Log::info('PDF generado correctamente, preparando descarga: ' . $filename);

            // Mostrar el PDF directamente en el navegador
            return $pdf->stream($filename, ['Attachment' => false]);

        } catch (\Exception $e) {
            Log::error('Error al generar reporte de comprobantes: ' . $e->getMessage(), [
                'book_id' => $book->id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si existen comprobantes en un rango específico para un libro.
     * Responde con JSON para la solicitud AJAX.
     */
    public function checkRange(Request $request, Book $book)
    {
        $inicio = $request->query('inicio');
        $fin = $request->query('fin');

        if (!is_numeric($inicio) || !is_numeric($fin) || $inicio <= 0 || $fin < $inicio) {
            return response()->json(['exists' => false, 'error' => 'Rango inválido'], 400); // Bad request
        }

        try {
            $query = $book->comprobantes()
                          ->whereBetween('numero_comprobante', [$inicio, $fin]);

            $count = $query->count();
            $exists = $count > 0;

            $existingNumbers = [];
            if ($exists) {
                // Obtener algunos números existentes para mostrar como ejemplo (limitar para eficiencia)
                $existingNumbers = $query->orderBy('numero_comprobante')
                                         ->limit(10) // Limitar cuántos recuperamos
                                         ->pluck('numero_comprobante')
                                         ->toArray();
            }

            return response()->json([
                'exists' => $exists,
                'count' => $count,
                'existing_numbers' => $existingNumbers
            ]);

        } catch (\Exception $e) {
            // Loguear el error real en el servidor
            Log::error('Error en checkRange para Book ID ' . $book->id . ': ' . $e->getMessage());
            // Devolver una respuesta genérica de error al cliente
            return response()->json(['exists' => false, 'error' => 'Error del servidor al verificar el rango.'], 500);
        }
    }
}
