<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\PerfilController;

use App\Models\User;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Http\Controllers\LooseLoanController;
use App\Models\Category;
use App\Http\Controllers\BackupController;
use Barryvdh\DomPDF\Facade\PDF;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\OpenAIController;

// Ruta principal: redirección según tipo de usuario
Route::get('/', function () {
    if (Auth::check()) {
        Log::info('Usuario autenticado accediendo a ruta principal', [
            'user_id' => Auth::id(),
            'user_type' => Auth::user()->usertype
        ]);

        return Auth::user()->usertype === 'admin'
            ? redirect()->route('document.loans.index')
            : redirect()->route('home');
    }
    return redirect('/login');
})->name('welcome');

// Las rutas de autenticación son manejadas automáticamente por Laravel Fortify
// y tienen configurado su rate limiting en FortifyServiceProvider

// Rutas para usuarios regulares (autenticados)
Route::middleware(['auth'])->group(function () {
    // Página principal y exploración
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/explore', [HomeController::class, 'explore'])->name('explore');

    // Sistema de Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{user}', [ChatController::class, 'show'])->name('show');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/unread', [ChatController::class, 'getUnreadCount'])->name('unread');
        Route::post('/mark-read/{message}', [ChatController::class, 'markAsRead'])->name('markRead');
        Route::get('/attachment/{attachment}/download', [ChatController::class, 'downloadAttachment'])->name('download');
        Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('delete');
    });

    // Búsqueda
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/user-category/{id}', [HomeController::class, 'userCategorySearch'])->name('user.category.search');

    // Gestión de carpetas para usuario
    Route::get('/book_details/{id}', [HomeController::class, 'book_details'])->name('books.details');
    Route::get('/books/borrow/{id}', [HomeController::class, 'borrow_books'])->name('books.borrow');

    // Historial y cancelaciones
    Route::get('/books/history', [HomeController::class, 'book_history'])->name('books.history');
    Route::get('/books/cancel/{id}', [HomeController::class, 'cancel_req'])->name('books.cancel');

    // Rutas para perfil de usuario
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::patch('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/password', function () {
        return view('perfil.password', ['user' => auth()->user()]);
    })->name('perfil.password');
    Route::put('/perfil/actualizar-password', [PerfilController::class, 'updatePassword'])->name('perfil.password.update');
    Route::patch('/perfil/actualizar-foto', [PerfilController::class, 'updatePhoto'])->name('perfil.update-photo');
    Route::get('/perfil/eliminar', function () {
        return view('perfil.delete-user', ['user' => auth()->user()]);
    })->name('perfil.delete');
    Route::delete('/perfil/eliminar', [PerfilController::class, 'destroy'])->name('perfil.destroy');
});

// Rutas para administradores
Route::middleware(['auth'])->group(function () {
    // Rutas que requieren ser administrador
    Route::middleware(['admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Gestión de usuarios
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Gestión de categorías
        Route::get('/category_page', [AdminController::class, 'category_page']);
        Route::post('/add_category', [AdminController::class, 'add_category']);
        Route::get('/cat_delete/{id}', [AdminController::class, 'cat_delete'])->name('cat_delete');
        Route::get('/cat_activate/{id}', [AdminController::class, 'cat_delete'])->name('cat_activate');
        Route::get('/edit_category/{id}', [AdminController::class, 'edit_category']);
        Route::post('/update_category/{id}', [AdminController::class, 'update_category']);

        // Gestión de carpetas
        Route::get('/add_book', [AdminController::class, 'add_book'])->name('add_book');
        Route::post('/store_book', [AdminController::class, 'store_book'])->name('store_book');
        Route::get('/show_book', [AdminController::class, 'show_book'])->name('show_book');
        Route::get('/show_book/category/{category}', [AdminController::class, 'show_book'])->name('show_book.category');
        Route::get('/book_delete/{id}', [AdminController::class, 'book_delete'])->name('book_delete');
        Route::get('/edit_book/{id}', [AdminController::class, 'edit_book'])->name('edit_book');
        Route::post('/update_book/{id}', [AdminController::class, 'update_book'])->name('update_book');

        // Sistema de códigos QR
        Route::get('/qr-generate/{id}', [App\Http\Controllers\QrCodeController::class, 'generateQrImage'])->name('qr.generate');
        Route::get('/qr-report', [App\Http\Controllers\QrCodeController::class, 'generateCategoryQrReport'])->name('qr.report');
        Route::get('/qr-report/category/{category_id}', [App\Http\Controllers\QrCodeController::class, 'generateCategoryQrReport'])->name('qr.report.category');

        // Rutas para comprobantes
        Route::get('/books/{book}/comprobantes', [ComprobanteController::class, 'index'])->name('books.comprobantes.index');
        Route::get('/books/{book}/comprobantes/create', [ComprobanteController::class, 'create'])->name('books.comprobantes.create');
        Route::post('/books/{book}/comprobantes', [ComprobanteController::class, 'store'])->name('books.comprobantes.store');
       // Route::get('/books/{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'show'])->name('comprobantes.show');
        Route::get('/books/{book}/comprobantes/{comprobante}/edit', [ComprobanteController::class, 'edit'])->name('comprobantes.edit');
        Route::put('/books/{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'update'])->name('comprobantes.update');
        Route::delete('/books/{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'destroy'])->name('comprobantes.destroy');

        // PDFs de comprobantes
        Route::get('/books/{book}/comprobantes/{comprobante}/pdf', [ComprobanteController::class, 'viewPdf'])->name('comprobantes.pdf');
        Route::delete('/books/{book}/comprobantes/{comprobante}/remove-pdf', [ComprobanteController::class, 'removePdf'])->name('comprobantes.remove-pdf');

        // Reportes
        Route::get('/reporte/reports', [ReportController::class, 'index'])->name('reporte.index');
        Route::get('/reporte/libros/{categoria?}', [ReportController::class, 'generarReporteLibros'])->name('reporte.libros');
        Route::get('/reporte/prestamos', [ReportController::class, 'generarReporteDePrestamos'])->name('reporte.prestamos');
        Route::get('/reporte/documentos', [ReportController::class, 'generarReporteDocumentos'])->name('reporte.documentos');
        Route::get('/reporte/usuarios', [ReportController::class, 'generarReporteUsuarios'])->name('reporte.usuarios');
        Route::get('/reports/prestamos-libres', [ReportController::class, 'generarReportePrestamosLibres'])->name('reports.prestamos-libres');
        Route::get('/reports/categorias', [ReportController::class, 'generarReporteCategorias'])->name('reports.categorias');
        Route::get('/reports/qr', [ReportController::class, 'generarReporteQR'])->name('reports.qr');
        Route::get('/reports/qr/categoria/{categoria_id}', [ReportController::class, 'generarReporteQR'])->name('reports.qr.categoria');

        // Sistema nuevo de préstamos
        Route::prefix('document')->name('document.')->group(function() {
            // Préstamos de documentos
            Route::get('/loans', [DocumentController::class, 'index'])->name('loans.index');
            Route::get('/loan/{book_id}', [DocumentController::class, 'createLoan'])->name('loan.create');
            Route::post('/loan', [DocumentController::class, 'storeLoan'])->name('loan.store');
            Route::get('/loan/show/{document}', [DocumentController::class, 'showLoan'])->name('loan.show');
            Route::put('/loan/{document}', [DocumentController::class, 'updateLoan'])->name('loan.update');

            // Gestión de comprobantes en préstamos
            Route::get('/{document}/comprobantes', [DocumentController::class, 'getComprobantes'])->name('comprobantes');
            Route::get('/comprobante/{comprobante}/details', [DocumentController::class, 'getComprobanteDetails'])->name('comprobante.details');
            Route::put('/{document}/comprobante/{comprobante}/return', [DocumentController::class, 'returnComprobante'])->name('comprobante.return');
            Route::post('/{document}/return-all', [DocumentController::class, 'returnAllComprobantes'])->name('comprobantes.return-all');
            Route::get('/{document}/manage-comprobantes', [DocumentController::class, 'manageComprobantes'])->name('comprobantes.manage');
            Route::get('/comprobante/{comprobante}/pdf', [DocumentController::class, 'viewComprobantePdf'])->name('comprobante.pdf');

            // PDF
            Route::get('/{document}/comprobantesPDF', function(Request $request, $document) {
                $request->merge(['format' => 'pdf']);
                return app(DocumentController::class)->getComprobantes(Document::findOrFail($document), $request);
            })->name('comprobantes.pdf');

            // PDF para préstamos generales (no comprobantes)
            Route::get('/{document}/generalPDF', function($document) {
                return app(DocumentController::class)->generarPDFGeneral(Document::findOrFail($document));
            })->name('general.pdf');
        });

        // Gestión de comprobantes por libro
        Route::prefix('books')->name('books.')->group(function() {
            // Rutas para comprobantes
            Route::get('{book}/comprobantes/report', [ComprobanteController::class, 'generateReport'])
                ->name('comprobantes.report')
                ->where('book', '[0-9]+');

            Route::get('{book}/comprobantes', [ComprobanteController::class, 'index'])->name('comprobantes.index');
            Route::get('{book}/comprobantes/create', [ComprobanteController::class, 'create'])->name('comprobantes.create');
            Route::post('{book}/comprobantes', [ComprobanteController::class, 'store'])->name('comprobantes.store');
            //Route::get('{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'show'])->name('comprobantes.show');
            Route::get('{book}/comprobantes/{comprobante}/edit', [ComprobanteController::class, 'edit'])->name('comprobantes.edit');
            Route::put('{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'update'])->name('comprobantes.update');
            Route::delete('{book}/comprobantes/{comprobante}', [ComprobanteController::class, 'destroy'])->name('comprobantes.destroy');

            // PDFs de comprobantes
            Route::get('{book}/comprobantes/{comprobante}/pdf', [ComprobanteController::class, 'viewPdf'])->name('comprobantes.pdf');
            Route::delete('{book}/comprobantes/{comprobante}/remove-pdf', [ComprobanteController::class, 'removePdf'])->name('comprobantes.remove-pdf');

            // Confirmación
            Route::get('/confirmation', [AdminController::class, 'bookConfirmation'])->name('confirmation');
        });

        // Búsqueda avanzada
        Route::get('/details/{id}', [SearchController::class,'details'])->name('search.details');
        Route::get('/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
        Route::get('/admin-search/{filter?}', [SearchController::class, 'searchh'])->name('searchh.filter');
        Route::get('/cat_search/{id}', [SearchController::class, 'cat_search'])->name('search.category');

        // API
        Route::prefix('api')->name('api.')->group(function() {
            Route::get('/books/{book}/check-comprobantes', [ComprobanteController::class, 'checkComprobantesExistentes'])
                 ->name('books.check-comprobantes');
        });

          // Préstamos sueltos
          Route::prefix('loose-loans')->name('loose-loans.')->group(function () {
            Route::get('/', [LooseLoanController::class, 'index'])->name('index');
            Route::get('/create', [LooseLoanController::class, 'create'])->name('create');
            Route::post('/store', [LooseLoanController::class, 'store'])->name('store');
            Route::get('/{id}', [LooseLoanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [LooseLoanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LooseLoanController::class, 'update'])->name('update');
            Route::delete('/{id}', [LooseLoanController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/confirmation', [LooseLoanController::class, 'confirmation'])->name('confirmation');
            Route::put('/{id}/return', [LooseLoanController::class, 'return'])->name('return');
        });

        // Sistema antiguo de préstamos (mantenido por compatibilidad)
        Route::get('/borrow_request', [AdminController::class, 'borrow_request']);
        Route::get('/approve_book/{id}', [AdminController::class, 'approve_book']);
        Route::get('/return_book/{id}', [AdminController::class, 'return_book']);
        Route::get('/rejected_book/{id}', [AdminController::class, 'rejected_book']);

        // Rutas para el backup
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/{fileName}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{fileName}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });

    // Rutas para el chat con IA
    Route::post('/ai/chat', [OpenAIController::class, 'chat'])->name('ai.chat');
});

// Ruta pública para ver la información QR
Route::get('/qr-info/{id}', [App\Http\Controllers\QrCodeController::class, 'showInfo'])->name('qr.info');

// Ruta para fotos de usuarioo
Route::get('usuario/foto/{filename}', function ($filename) {
    $path = public_path('storage/profile-photos/' . $filename);

    if (!file_exists($path)) {
        $path = public_path($filename);

        if (!file_exists($path)) {
            return asset('admin/img/user.jpg');
        }
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200, [
        'Content-Type' => $type,
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
})->name('user.photo');

// Ruta temporal para verificar la zona horaria (puedes eliminarla después)
Route::get('/check-timezone', function () {
    return [
        'timezone_configurada' => config('app.timezone'),
        'hora_actual' => now()->format('Y-m-d H:i:s'),
        'hora_utc' => now()->setTimezone('UTC')->format('Y-m-d H:i:s'),
        'zona_horaria_php' => date_default_timezone_get(),
        'php_date' => date('Y-m-d H:i:s'),
        'diferencia_utc' => now()->tzDifference('UTC')
    ];
});

// Ruta de prueba para OpenAI
Route::get('/test-openai', function() {
    try {
        \Illuminate\Support\Facades\Log::info('Iniciando prueba de OpenAI');

        $result = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => '¡Hola! Escribe una respuesta breve.'],
            ],
            'max_tokens' => 50
        ]);

        \Illuminate\Support\Facades\Log::info('Respuesta obtenida de OpenAI', [
            'respuesta' => $result->choices[0]->message->content
        ]);

        return 'API funcionando: ' . $result->choices[0]->message->content;
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error en prueba OpenAI', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return 'Error en la API de OpenAI: ' . $e->getMessage();
    }
});

