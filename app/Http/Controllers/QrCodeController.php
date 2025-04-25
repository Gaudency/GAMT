<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class QrCodeController extends Controller
{
    /**
     * Mostrar la información de un libro/carpeta por QR
     */
    public function showInfo($id)
    {
        try {
            $book = Book::with('category')->findOrFail($id);
            return view('admin.qr_info', compact('book'));
        } catch (\Exception $e) {
            abort(404, 'Carpeta no encontrada');
        }
    }

    /**
     * Generar un QR para un libro/carpeta específico
     */
    public function generateQrImage($id)
    {
        try {
            $book = Book::findOrFail($id);
            $url = route('qr.info', $book->id);

            // NOTA: Para usar un logo en QR, necesitas una imagen accesible desde internet
            // Como estás en red local, tienes dos opciones:
            // 1. Comenta estas líneas y usa el QR sin logo
            // 2. Sube tu logo a un servicio como imgur.com y usa esa URL
            $logoUrl = asset('path/to/your/logo.png'); // Esta URL no funcionará en red local

            // Descomentar esta línea para usar QR sin logo:
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url);

            // Comentar esta línea si no tienes un logo en internet:
            // $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url) . "&logo=" . urlencode($logoUrl);

            return response()->json([
                'qr_image_url' => $qrCodeUrl,
                'book_code' => $book->N_codigo,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar el código QR',
                'success' => false
            ], 500);
        }
    }

    /**
     * Generar un reporte con todos los QRs de una categoría específica
     */
    public function generateCategoryQrReport($category_id = null)
    {
        try {
            // Obtener la categoría si se proporciona un ID
            $category = null;
            if ($category_id) {
                $category = \App\Models\Category::findOrFail($category_id);
                $books = \App\Models\Book::where('category_id', $category_id)->get();
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

            return view('admin.qr_report', compact('books', 'title', 'category'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el reporte de QRs: ' . $e->getMessage());
        }
    }
}
