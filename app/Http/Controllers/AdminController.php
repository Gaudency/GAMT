<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Book;

use App\Models\Loan;

use App\Models\Document;

use App\Models\Category;

use App\Models\Borrow;

use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Comprobante;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Models\ChatMessage;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        // El middleware ya está en las rutas
        // Compartir el contador de mensajes no leídos con todas las vistas
        View::composer('admin.*', function($view) {
            if (class_exists('App\Models\ChatMessage') && auth()->check()) {
                $unreadMessages = \App\Models\ChatMessage::where('receiver_id', auth()->id())
                                        ->where('is_read', false)
                                        ->count();
                $view->with('unreadMessages', $unreadMessages);
            } else {
                $view->with('unreadMessages', 0);
            }
        });
    }
    public function index() {
        if (!Auth::check()) {
            return redirect('/login'); // Redirige a la página de login si no está autenticado
        }

        $user_type = Auth::user()->usertype;

        if ($user_type == 'admin') {
            // Lógica para admin
            $user = User::count();
            $book = Book::count();
            $borrow = Borrow::where('status', 'approved')->count();
            $returned = Borrow::where('status', 'returned')->count();

            return view('admin.index', compact('user', 'book', 'borrow', 'returned'));
        }

        if ($user_type == 'user') {
            // Lógica para usuarios comunes
            $data = Book::all();
            return view('home.index', compact('data'));
        }else{
                Auth::logout();
        // Si no es ni admin ni user, redirige al login
        return redirect('/login')->with('error', 'Access denied: Invalid user role.');
        }
    }
    public function category_page()
    {
        $categories = Category::all();
        return view('admin.category', compact('categories'));
    }

    public function add_category(Request $request)
    {
        $request->validate([
            'cat_title' => 'required|string|max:255',
            'tipo' => 'required|in:general,comprobante',
            'detalles' => 'nullable|string',
        ]);

        Category::create([
            'cat_title' => $request->cat_title,
            'tipo' => $request->tipo,
            'detalles' => $request->detalles ? ['info' => $request->detalles] : null,
            'status' => 'activo'
        ]);

        return redirect()->back()->with('message', 'Categoría agregada exitosamente');
    }

    public function cat_delete($id)
    {
        $category = Category::findOrFail($id);
        $currentStatus = $category->status;

        $category->update([
            'status' => $currentStatus === 'activo' ? 'inactivo' : 'activo'
        ]);

        $message = $currentStatus === 'activo' ?
            'Categoría desactivada exitosamente' :
            'Categoría activada exitosamente';

        return redirect()->back()->with('message', $message);
    }


    public function edit_category($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit_category', compact('category'));
    }


    public function update_category(Request $request, $id)
    {
        $request->validate([
            'cat_title' => 'required|string|max:255',
            'tipo' => 'required|in:general,comprobante',
            'detalles' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'cat_title' => $request->cat_title,
            'tipo' => $request->tipo,
            'detalles' => $request->detalles ? ['info' => $request->detalles] : null,
        ]);

        return redirect('/category_page')->with('message', 'Categoría actualizada exitosamente');
    }
    //añadir documetos
    public function add_document()
    {
        $data = Category::all();

        return view('admin.add_document',compact('data'));
    }
//añadir carpetas
    public function add_book()
    {
        try {
            $categories = Category::all()->map(function($category) {
                return [
                    'id' => $category->id,
                    'title' => $category->cat_title,
                    'tipo' => $category->tipo
                ];
            });

            return view('admin.add_book', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Error en add_book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el formulario de carpetas');
        }
    }
// añadir carpetas
    public function store_book(Request $request)
    {
        try {
            // Validación básica
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'year' => 'required|integer|min:1990|max:' . date('Y'),
                'ambiente' => 'required|string',
                'estante' => 'nullable|integer',
                'bandeja' => 'nullable|integer',
                'ubicacion' => 'required|string',
                'tomo' => 'nullable|string',
                'N_hojas' => 'required|string',
                'description' => 'nullable|string',
                'book_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'estado' => 'nullable|in:Prestado,No Prestado',
                'pdf_file' => 'required|mimes:pdf|max:51200',
                'ambiente_nombre' => 'nullable|string',
                'estante_numero' => 'nullable|integer',
                'bandeja_numero' => 'nullable|integer',
            ]);

            // Obtener la categoría para determinar el tipo
            $categoria = Category::findOrFail($request->category);
            $esComprobante = $categoria->tipo === 'comprobante';

            // Validación adicional para categorías de tipo comprobante
            if ($esComprobante) {
                $request->validate([
                    'comprobante_inicio' => 'required|integer|min:1',
                    'comprobante_fin' => 'required|integer|gte:comprobante_inicio',
                ], [
                    'comprobante_inicio.required' => 'El número de comprobante inicial es obligatorio',
                    'comprobante_fin.required' => 'El número de comprobante final es obligatorio',
                    'comprobante_fin.gte' => 'El comprobante final debe ser mayor o igual al inicial'
                ]);
            }

            DB::beginTransaction();

            // Crear la carpeta
            $book = new Book();
            $book->title = $request->title;
            $book->N_codigo = Book::generateCodigo();
            $book->category_id = $request->category;
            $book->year = $request->year;
            $book->ambiente = $request->ambiente;
            $book->ambiente_nombre = $request->ambiente_nombre;
            $book->bandeja = $request->bandeja;
            $book->estante_numero = $request->estante_numero;
            $book->bandeja_numero = $request->bandeja_numero;
            $book->ubicacion = $request->ubicacion;
            $book->tomo = $request->tomo;
            $book->description = $request->description;
            $book->N_hojas = $request->N_hojas;
            $book->estado = $request->estado ?? 'No Prestado';

            // Procesar archivos si existen
            if ($request->hasFile('book_img')) {
                $book_image = $request->file('book_img');
                $book_image_name = time() . '.' . $book_image->getClientOriginalExtension();
                $book_image->move(public_path('book'), $book_image_name);
                $book->book_img = $book_image_name;
            }

            if ($request->hasFile('pdf_file')) {
                $pdf_file = $request->file('pdf_file');
                $pdf_file_name = time() . '_' . Str::random(4) . '.' . $pdf_file->getClientOriginalExtension();
                $pdf_file->move(public_path('pdfs'), $pdf_file_name);
                $book->pdf_file = $pdf_file_name;
            }

            // Guardar la carpeta
            $book->save();

            // Si es una carpeta de comprobantes, crear los comprobantes
            if ($esComprobante) {
                $inicio = $request->comprobante_inicio;
                $fin = $request->comprobante_fin;
                $comprobantes = [];

                for ($i = $inicio; $i <= $fin; $i++) {
                    $comprobantes[] = [
                        'numero_comprobante' => $i,
                        'n_hojas' => ceil(intval($request->N_hojas) / ($fin - $inicio + 1)), // Dividir hojas entre el número de comprobantes
                        'estado' => 'activo',
                        'book_id' => $book->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                DB::table('comprobantes')->insert($comprobantes);
            }

            DB::commit();

            // Preparar mensaje de éxito con datos
            $mensaje = [
                'success' => true,
                'title' => '¡Carpeta creada exitosamente!',
                'details' => [
                    'nombre' => $book->title,
                    'codigo' => $book->N_codigo,
                    'categoria' => $book->category->cat_title,
                    'ubicacion' => $book->ubicacion,
                    'tomo' => $book->tomo,
                    'hojas' => $book->N_hojas,
                    'year' => $book->year,
                    'ambiente' => $book->ambiente,
                    'bandeja' => $book->bandeja
                ],
                'esComprobante' => $esComprobante,
                'book_id' => $book->id
            ];

            if ($esComprobante) {
                $mensaje['comprobantes'] = [
                    'inicio' => $request->comprobante_inicio,
                    'fin' => $request->comprobante_fin,
                    'total' => $request->comprobante_fin - $request->comprobante_inicio + 1
                ];
            }

            if ($esComprobante) {
                return redirect()->route('books.comprobantes.index', [
                    'book' => $book->id
                ])->with('message', 'Carpeta creada exitosamente. Puede gestionar los comprobantes desde aquí.');
            }

            return redirect()->route('show_book')->with('message', 'Carpeta creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear carpeta: ' . $e->getMessage());
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error al crear la carpeta: ' . $e->getMessage());
        }
    }
    // mirar carpetaa
    public function show_book(Request $request, $category = null)
    {
        $query = Book::with('category');

        // Buscar por código digital
        if ($request->has('search_codigo') && !empty($request->search_codigo)) {
            $search_codigo = $request->search_codigo;
            $query->where(function($q) use ($search_codigo) {
                $q->where('N_codigo', '=', $search_codigo)  // Búsqueda exacta
                  ->orWhere('N_codigo', 'LIKE', "{$search_codigo}%") // Comienza con
                  ->orWhere('N_codigo', 'LIKE', "%{$search_codigo}") // Termina con
                  ->orWhere('N_codigo', 'LIKE', "%{$search_codigo}%"); // Contiene
            });
        }

        // Aplicar filtro de categoría si existe
        if ($category) {
            $query->where('category_id', $category);
        }

        // Obtener parámetros de ordenamiento
        $sortBy = $request->get('sort_by', 'N_codigo'); // Por defecto ordenar por código
        $sortOrder = $request->get('sort_order', 'asc'); // Por defecto ascendente

        // Aplicar ordenamiento
        switch ($sortBy) {
            case 'fecha_modificacion':
                $query->orderBy('updated_at', $sortOrder);
                break;
            case 'N_codigo':
            default:
                $query->orderBy('N_codigo', $sortOrder);
                break;
        }

        // Obtener los libros y aplicar la paginación
        $books = $query->paginate(10);

        // Añadir los query parameters a los links de paginación
        if ($request->query()) {
            $books->appends($request->query());
        }

        $categories = Category::all();

        return view('admin.show_book', compact('books', 'categories', 'sortBy', 'sortOrder'));
    }

    //eliminar carpetas
    public function book_delete($id)
    {
         $data = Book::find($id);


         $data->delete();

         return redirect()->back()->with('message','carpeta eliminada');

    }
 //actualizar carpeta
    public function edit_book($id)
    {

        $data = Book::find($id);

        $category = Category::all();


        return view('admin.edit_book',compact('data','category'));

    }
 // actuaizar carpeta
    public function update_book(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'ubicacion' => 'nullable|string',
            'tomo' => 'nullable|string',
            'N_hojas' => 'nullable|string',
            'description' => 'nullable|string',
            'category' => 'required|integer',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'estado' => 'nullable|in:Prestado,No Prestado',
            'book_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'pdf_file' => 'nullable|mimes:pdf|max:51200',
            'ambiente' => 'nullable|string',
            'estante' => 'nullable|integer',
            'bandeja' => 'nullable|integer',
            'ambiente_nombre' => 'nullable|string',
            'estante_numero' => 'nullable|integer',
            'bandeja_numero' => 'nullable|integer',
        ]);

        // Obtener la categoría para determinar el tipo
        $categoria = Category::find($request->category);
        if (!$categoria) {
            return redirect()->back()->with('error', 'Categoría no encontrada');
        }

        try {
            $data = Book::findOrFail($id);

            // No modificamos el N_codigo para mantener la consistencia
            // $data->N_codigo = $request->N_codigo;

            $data->title = $request->title;
            $data->ubicacion = $request->ubicacion;
            $data->year = $request->year;
            $data->tomo = $request->tomo;
            $data->description = $request->description;
            $data->N_hojas = $request->N_hojas;
            $data->category_id = $request->category;
            $data->estado = $request->estado ?? 'No Prestado';

            // Campos de ubicación nuevos
            $data->ambiente = $request->ambiente;
            $data->ambiente_nombre = $request->ambiente_nombre;
            $data->bandeja = $request->bandeja;
            $data->estante_numero = $request->estante_numero;
            $data->bandeja_numero = $request->bandeja_numero;

            // Procesar imagen si existe
            if ($request->hasFile('book_img')) {
                $book_image = $request->file('book_img');
                $book_image_name = time() . '.' . $book_image->getClientOriginalExtension();
                $book_image->move(public_path('book'), $book_image_name);
                $data->book_img = $book_image_name;
            }

            // Procesar PDF si existe
            if ($request->hasFile('pdf_file')) {
                $pdf_file = $request->file('pdf_file');
                $pdf_file_name = time() . '_' . Str::random(4) . '.' . $pdf_file->getClientOriginalExtension();
                $pdf_file->move(public_path('pdfs'), $pdf_file_name);
                $data->pdf_file = $pdf_file_name;
            }

            $data->save();

            // Redirección basada en el tipo de categoría
            if ($categoria->tipo === 'comprobante') {
                return redirect()->route('books.comprobantes.index', [
                    'book' => $data->id
                ])->with('message', 'Carpeta actualizada correctamente. Puede gestionar los comprobantes desde aquí.');
            }

            return redirect('/show_book')->with('message', 'Carpeta actualizada correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar carpeta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo actualizar la carpeta: ' . $e->getMessage());
        }
    }

public function change_document_status(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:espera,prestado,devuelto',
    ]);

    $document = Document::findOrFail($id);
    $document->status = $request->status;
    $document->save();
    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
}
///////////////////////////////////////////////////////////////

public function borrow_request()
{
    $data = Borrow::with(['user', 'book'])
        ->withCount(['unreadMessages' => function($query) {
            $query->where('receiver_id', auth()->id())
                 ->where('is_read', false);
        }])
        ->paginate(10);

    $stats = [
        'total' => Borrow::count(),
        'pending' => Borrow::where('status', 'Applied')->count(),
        'approved' => Borrow::where('status', 'Approved')->count(),
        'returned' => Borrow::where('status', 'Returned')->count(),
        'rejected' => Borrow::where('status', 'Rejected')->count(),
    ];

    return view('admin.borrow_request', compact('data', 'stats'));
}

public function approve_book($id)
{
    $borrow = Borrow::findOrFail($id);

    // Verificar si la carpeta ya está prestada o devuelta
    if ($borrow->status === 'Approved') {
        return redirect()->back()->with('error', 'Esta carpeta ya está prestada');
    }

    if ($borrow->status === 'Returned') {
        return redirect()->back()->with('error', 'No se puede aprobar una carpeta que ya fue devuelta');
    }

    // Actualizar estado del préstamo
    $borrow->status = 'Approved';
    $borrow->approved_at = now();
    $borrow->save();

    // Actualizar estado de la carpeta
    $book = Book::find($borrow->book_id);
    $book->estado = 'Prestado';
    $book->save();

    return redirect()->back()->with('message', 'Préstamo aprobado correctamente');
}
public function return_book($id)
{
    $borrow = Borrow::findOrFail($id);

    if ($borrow->status === 'Returned') {
        return redirect()->back()->with('error', 'Esta carpeta ya fue devuelta');
    }

    // Actualizar estado del préstamo
    $borrow->status = 'Returned';
    $borrow->returned_at = now();
    $borrow->save();

    // Actualizar estado de la carpeta
    $book = Book::find($borrow->book_id);
    $book->estado = 'No Prestado';
    $book->save();

    return redirect()->back()->with('message', 'Carpeta devuelta correctamente');
   }

   public function rejected_book($id)
   {
       $borrow = Borrow::findOrFail($id);

       if ($borrow->status === 'Rejected') {
           return redirect()->back()->with('error', 'Esta solicitud ya fue rechazada');
       }

       if ($borrow->status === 'Returned') {
           return redirect()->back()->with('error', 'No se puede rechazar una carpeta que ya fue devuelta');
       }

       // Actualizar estado del préstamo
       $borrow->status = 'Rejected';
       $borrow->rejected_at = now();
       $borrow->save();

       return redirect()->back()->with('message', 'Solicitud rechazada correctamente');
   }
}
