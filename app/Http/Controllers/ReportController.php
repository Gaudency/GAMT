<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

use App\Models\Borrow;

use App\Models\Document;

use App\Models\User;

use App\Models\Loose_Loan;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;


use PDF;

class ReportController extends Controller
{
    public function index() {
        // Obtener todas las categorías para el selector
        $categorias = \App\Models\Category::orderBy('cat_title')->get();

        return view('admin.reports.index', compact('categorias'));
    }
    //generar reporte que las lie
    public function generarReporteLibros(Request $request, $categoria = null)
    {
        try {
            // Si no se proporciona categoría en la URL, intentar obtenerla del parámetro GET
            if (!$categoria && $request->has('categoria') && !empty($request->categoria)) {
                $categoria = $request->categoria;
            }

            // Consulta base para obtener libros con su categoría
            $query = Book::with('category');

            // Si se proporciona una categoría, filtramos los libros por esa categoría
            if ($categoria) {
                $query->whereHas('category', function ($q) use ($categoria) {
                    $q->where('cat_title', 'like', "%$categoria%");
                });
            }

            // Ejecutar la consulta
            $books = $query->get();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Obtener usuario actual
            $currentUser = auth()->user();
            $userName = $currentUser ? $currentUser->name : 'Administrador del Sistema';

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.libros', [
                'books' => $books,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists,
                'categoria' => $categoria,
                'currentUser' => $userName
            ]);

            $pdf->setPaper('A4', 'landscape');

            // Nombre del archivo con la categoría si existe
            $filename = $categoria ? "reporte_carpetas_$categoria.pdf" : "reporte_carpetas.pdf";

            // Retornar el PDF para visualizar en el navegador o descargarlo
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de libros: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }
    //reporte de prestamos en linea
    public function generarReporteDePrestamos($categoria = null)
    {
        try {
            // Consulta base para obtener préstamos con sus relaciones
            $query = Borrow::with(['book.category', 'user']);

            // Si se proporciona una categoría, filtramos los préstamos por esa categoría
            if ($categoria) {
                $query->whereHas('book.category', function ($q) use ($categoria) {
                    $q->where('cat_title', 'like', "%$categoria%");
                });
            }

            // Ejecutar la consulta
            $borrows = $query->get();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.borrows', [
                'borrows' => $borrows,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists,
                'categoria' => $categoria
            ]);

            $pdf->setPaper('A4', 'landscape');

            // Nombre del archivo con la categoría si existe
            $filename = $categoria ? "reporte_prestamos_$categoria.pdf" : "reporte_prestamos.pdf";

            // Retornar el PDF para su descarga
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de préstamos: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }
    public function generarReporteDocumentos(Request $request)
    {
        try {
            // Obtener parámetros de filtro
            $mes = $request->mes;
            $anio = $request->anio;

            // Log de parámetros recibidos
            \Log::info('Generando reporte de documentos con filtros', [
                'mes' => $mes,
                'anio' => $anio
            ]);

            // Consulta base para obtener documentos
            $query = Document::with(['category', 'book', 'comprobantes']);

            // Filtro por año
            if ($anio) {
                $query->whereYear('created_at', $anio);
            }

            // Filtro por mes
            if ($mes) {
                $query->whereMonth('created_at', $mes);
            }

            // Ejecutar la consulta
            $documents = $query->orderBy('created_at', 'desc')->get();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Generar título según los filtros aplicados
            $periodoTexto = '';
            if ($mes && $anio) {
                $nombreMes = [
                    '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril',
                    '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto',
                    '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ][$mes] ?? $mes;
                $periodoTexto = "$nombreMes $anio";
            } elseif ($anio) {
                $periodoTexto = "Año $anio";
            } elseif ($mes) {
                $nombreMes = [
                    '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril',
                    '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto',
                    '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ][$mes] ?? $mes;
                $periodoTexto = "Mes de $nombreMes";
            }

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.documents', [
                'documents' => $documents,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists,
                'periodoTexto' => $periodoTexto,
                'filtros' => [
                    'mes' => $mes,
                    'anio' => $anio
                ]
            ]);

            $pdf->setPaper('A4', 'landscape'); // Cambiado a horizontal para acomodar todos los campos

            // Generar nombre de archivo basado en filtros
            $parts = ['reporte_documentos'];
            if ($mes) $parts[] = 'mes_' . $mes;
            if ($anio) $parts[] = 'anio_' . $anio;
            $filename = implode('_', $parts) . '.pdf';

            // Retornar el PDF para visualización
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de documentos: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }

    public function generarReporteUsuarios()
    {
        try {
            // Obtener todos los usuarios
            $users = User::all();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Log para depuración
            \Log::info('Ruta de escudo: ' . public_path('images/escudo.png') . ' - Existe: ' . ($escudoExists ? 'Sí' : 'No'));
            \Log::info('Ruta de bandera: ' . public_path('images/bandera.png') . ' - Existe: ' . ($banderaExists ? 'Sí' : 'No'));

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.usuarios', [
                'users' => $users,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists
            ]);

            $pdf->setPaper('A4', 'portrait');

            // Retornar el PDF para su descarga o visualización
            return $pdf->stream('reporte_usuarios.pdf');
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de usuarios: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }

    public function generarReportePrestamosLibres(Request $request)
    {
        try {
            // Obtener parámetros de filtro
            $mes = $request->mes;
            $anio = $request->anio;

            // Log de parámetros recibidos
            \Log::info('Generando reporte de préstamos libres con filtros', [
                'mes' => $mes,
                'anio' => $anio
            ]);

            // Consulta base para obtener préstamos sueltos
            $query = Loose_Loan::query();

            // Filtro por año
            if ($anio) {
                // Filtrar por año en loan_date (fecha de préstamo)
                $query->whereYear('loan_date', $anio);
            }

            // Filtro por mes
            if ($mes) {
                // Filtrar por mes en loan_date (fecha de préstamo)
                $query->whereMonth('loan_date', $mes);
            }

            // Ejecutar la consulta
            $loose_loans = $query->orderBy('loan_date', 'desc')->get();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Generar título según los filtros aplicados
            $periodoTexto = '';
            if ($mes && $anio) {
                $nombreMes = [
                    '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril',
                    '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto',
                    '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ][$mes] ?? $mes;
                $periodoTexto = "$nombreMes $anio";
            } elseif ($anio) {
                $periodoTexto = "Año $anio";
            } elseif ($mes) {
                $nombreMes = [
                    '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril',
                    '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto',
                    '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ][$mes] ?? $mes;
                $periodoTexto = "Mes de $nombreMes";
            }

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.loose_loans', [
                'loose_loans' => $loose_loans,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists,
                'periodoTexto' => $periodoTexto,
                'filtros' => [
                    'mes' => $mes,
                    'anio' => $anio
                ]
            ]);

            $pdf->setPaper('A4', 'landscape'); // Usando horizontal para más espacio

            // Generar nombre de archivo basado en filtros
            $parts = ['reporte_prestamos_libres'];
            if ($mes) $parts[] = 'mes_' . $mes;
            if ($anio) $parts[] = 'anio_' . $anio;
            $filename = implode('_', $parts) . '.pdf';

            // Retornar el PDF para visualización
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de préstamos libres: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }

    public function generarReporteCategorias(Request $request)
    {
        try {
            // Consulta base para obtener categorías
            $query = \App\Models\Category::query();

            // Filtro por tipo si está especificado
            if ($request->has('tipo') && !empty($request->tipo)) {
                $query->where('tipo', $request->tipo);
            }

            // Filtro por estado si está especificado
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Ejecutar la consulta
            $categories = $query->orderBy('cat_title')->get();

            // Verificar si las imágenes existen
            $escudoExists = file_exists(public_path('images/escudo.png'));
            $banderaExists = file_exists(public_path('images/bandera.png'));

            // Obtener usuario actual
            $currentUser = auth()->user();
            $userName = $currentUser ? $currentUser->name : 'Administrador del Sistema';

            // Generar el PDF usando la vista
            $pdf = PDF::loadView('admin.reports.categories', [
                'categories' => $categories,
                'escudoExists' => $escudoExists,
                'banderaExists' => $banderaExists,
                'filtros' => [
                    'tipo' => $request->tipo,
                    'status' => $request->status
                ],
                'currentUser' => $userName
            ]);

            $pdf->setPaper('A4', 'portrait');

            // Generar nombre de archivo
            $parts = ['reporte_categorias'];
            if ($request->tipo) $parts[] = 'tipo_' . $request->tipo;
            if ($request->status) $parts[] = 'status_' . $request->status;
            $filename = implode('_', $parts) . '.pdf';

            // Retornar el PDF para visualización
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar reporte de categorías: ' . $e->getMessage());
            return back()->with('error', 'No se pudo generar el reporte. Error: ' . $e->getMessage());
        }
    }

    /**
     * Generar un reporte con todos los QRs de una categoría específica o todos
     */
    public function generarReporteQR(Request $request, $categoria_id = null)
    {
        try {
            // Obtener la categoría si se proporciona un ID
            $category = null;
            if ($categoria_id) {
                $category = \App\Models\Category::findOrFail($categoria_id);
                $books = \App\Models\Book::where('category_id', $categoria_id)->get();
            } else {
                // Si no se proporciona ID, obtener todos los libros
                $books = \App\Models\Book::all();
            }

            // Si no hay libros, redirigir con mensaje
            if ($books->isEmpty()) {
                return redirect()->back()->with('message', 'No hay carpetas en esta categoría para generar QRs.');
            }

            // Preparar datos para la vista
            $title = $category ? 'QRs de categoría: ' . $category->cat_title : 'QRs de todas las carpetas';

            // Determinar si se solicita PDF
            if ($request->has('format') && $request->format === 'pdf') {
                // Generar el PDF usando la vista
                $pdf = PDF::loadView('admin.reports.qr_report_pdf', compact('books', 'title', 'category'));

                // Configurar tamaño Oficio (Legal)
                $pdf->setPaper('legal', 'portrait');

                // Generar nombre del archivo
                $filename = $category ? 'qr_carpetas_' . $category->cat_title . '.pdf' : 'qr_todas_carpetas.pdf';

                // Retornar el PDF para su descarga
                return $pdf->stream($filename);
            }

            // Retornar vista normal si no se solicita PDF
            return view('admin.reports.qr_report', compact('books', 'title', 'category'));

        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje amigable
            \Log::error('Error al generar el reporte de QRs: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el reporte de QRs: ' . $e->getMessage());
        }
    }
}
