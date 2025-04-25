<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\View;

use App\Models\Book;

 use App\Models\Borrow;

 use App\Models\Category;

 use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Middleware para asegurar que solo usuarios autenticados accedan
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Compartir datos de chat en todas las vistas
        View::composer('*', function ($view) {
            $unreadMessages = ChatMessage::where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();

            $admins = User::where('usertype', 'admin')->get();

            $view->with([
                'unreadMessages' => $unreadMessages,
                'admins' => $admins
            ]);
        });
    }

    /**
     * Muestra la página principal para usuarios
     */
    public function index()
    {
        try {
            \Log::info('Acceso a HomeController@index', [
                'user_id' => Auth::id(),
                'user_type' => Auth::user()->usertype ?? 'desconocido'
            ]);

            // Redirigir administradores
            if (Auth::user()->usertype === 'admin') {
                return redirect()->route('document.loans.index');
            }

            // Cargar datos
            $categories = Category::all() ?? collect();
            $data = Book::with('category')->latest()->take(8)->get() ?? collect();

            return view('home.index', compact('data', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error en HomeController@index: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return $this->handleError('Ha ocurrido un error al cargar la página principal');
        }
    }

    /**
     * Muestra detalles de una carpeta
     */
    public function book_details($id)
    {
        try {
            $data = Book::with('category')->findOrFail($id);
            return view('home.books.details', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en detalles de libro: ' . $e->getMessage());
            return redirect()->route('explore')->with('error', 'La carpeta solicitada no existe');
        }
    }

    /**
     * Solicita préstamo de una carpeta
     */
    public function borrow_books($id)
    {
        try {
            // Verificar si el libro existe
            $data = Book::findOrFail($id);

            // Verificar si la carpeta está prestada según su estado
            if ($data->estado === 'Prestado') {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lo sentimos, esta carpeta está actualmente prestada y no está disponible para solicitudes.'
                    ]);
                }
                return redirect()->back()->with('error', 'Lo sentimos, esta carpeta está actualmente prestada y no está disponible para solicitudes.');
            }

            // Verificar si ya existe una solicitud pendiente o aprobada
            $existingBorrow = Borrow::where('book_id', $id)
                ->whereIn('status', ['Applied', 'Approved'])
                ->first();

            if ($existingBorrow) {
                // Obtener un administrador para el chat
                $admin = User::where('usertype', 'admin')->first();

                if ($existingBorrow->user_id == Auth::id()) {
                    if (request()->ajax()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Ya tienes una solicitud pendiente para esta carpeta.',
                            'borrow_id' => $existingBorrow->id,
                            'admin_id' => $admin->id
                        ]);
                    }
                    return redirect()->back()->with([
                        'info' => 'Ya tienes una solicitud pendiente para esta carpeta.',
                        'borrow_id' => $existingBorrow->id,
                        'admin_id' => $admin->id,
                        'show_chat' => true
                    ]);
                }

                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lo sentimos, esta carpeta ya tiene una solicitud de préstamo en proceso.'
                    ]);
                }
                return redirect()->back()->with('error', 'Lo sentimos, esta carpeta ya tiene una solicitud de préstamo en proceso.');
            }

            // Crear solicitud
            $borrow = new Borrow;
            $borrow->book_id = $id;
            $borrow->user_id = Auth::id();
            $borrow->status = 'Applied';
            $borrow->save();

            // Obtener un administrador para el chat
            $admin = User::where('usertype', 'admin')->first();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Solicitud enviada con éxito!',
                    'borrow_id' => $borrow->id,
                    'admin_id' => $admin->id
                ]);
            }

            // Si se prefiere mostrar confirmación con botón de chat
            return redirect()->back()->with([
                'message' => '¡Solicitud enviada con éxito!',
                'details' => 'Tu solicitud para la carpeta "' . $data->title . '" ha sido enviada al administrador. ' .
                           'Recibirás una notificación cuando sea procesada. Puedes ver el estado de tu solicitud en tu historial de préstamos.',
                'borrow_id' => $borrow->id,
                'admin_id' => $admin->id,
                'show_chat' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en solicitud de préstamo: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hubo un problema al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.'
                ]);
            }

            return redirect()->route('explore')->with('error', 'Hubo un problema al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    /**
     * Muestra historial de préstamos del usuario
     */
    public function book_history()
    {
        try {
            $userid = Auth::id();
            $data = Borrow::with('book.category')
                        ->where('user_id', $userid)
                        ->latest()
                        ->get();

            return view('home.books.history', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Error en historial: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Error al cargar el historial');
        }
    }

    /**
     * Cancela una solicitud de préstamo
     */
    public function cancel_req($id)
    {
        try {
            $borrow = Borrow::findOrFail($id);

            // Verificar permisos
            if ($borrow->user_id != Auth::id()) {
                return redirect()->back()->with('error', 'No tienes permiso para cancelar esta solicitud');
            }

            // Solo se pueden cancelar solicitudes pendientes
            if ($borrow->status != 'Applied') {
                return redirect()->back()->with('error', 'Solo puedes cancelar solicitudes pendientes');
            }

            $borrow->delete();

            return redirect()->back()->with('message', 'La solicitud de préstamo se canceló correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al cancelar solicitud: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cancelar la solicitud');
        }
    }

    /**
     * Muestra la página de exploración de carpetas
     */
    public function explore()
    {
        try {
            $categories = Category::all() ?? collect();
            $books = Book::with('category')->latest()->paginate(12);

            return view('home.books.explore', compact('books', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error en HomeController@explore: ' . $e->getMessage());
            return $this->handleError('Error al cargar la página de exploración');
        }
    }

    /**
     * Busca carpetas según criterios
     */
    public function search(Request $request)
    {
        try {
            \Log::info('Búsqueda iniciada', [
                'parámetros' => $request->all()
            ]);

            $categories = Category::all();
            $query = Book::with('category');

            // Filtrar por términos de búsqueda
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('ubicacion', 'LIKE', "%{$search}%")
                      ->orWhere('tomo', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");

                });
            }

            // Filtrar por código digital
            if ($request->filled('codigo')) {
                $codigo = $request->codigo;
                $query->where('N_codigo', 'LIKE', "%{$codigo}%");
            }

            // Filtrar por año
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            // Filtrar por categoría
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $books = $query->paginate(12);

            return view('home.books.explore', compact('books', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda: ' . $e->getMessage());
            return redirect()->route('explore')->with('error', 'Error al realizar la búsqueda');
        }
    }

    /**
     * Busca carpetas por categoría
     */
    public function userCategorySearch($id)
    {
        try {
            // Verificar si la categoría existe
            $category = Category::findOrFail($id);

            // Cargar todas las categorías para el menú
            $categories = Category::all();

            // Buscar libros de esa categoría
            $books = Book::with('category')
                        ->where('category_id', $id)
                        ->paginate(12);

            return view('home.books.explore', compact('books', 'categories', 'category'));
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda por categoría: ' . $e->getMessage());
            return redirect()->route('explore')->with('error', 'La categoría seleccionada no existe');
        }
    }

    /**
     * Método helper para manejar errores
     */
    private function handleError($message)
    {
        return view('home.error', [
            'message' => $message,
            'debugInfo' => config('app.debug') ? $e->getMessage() . "\n" . $e->getTraceAsString() : null
        ]);
    }
}

