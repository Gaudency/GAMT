<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /////////////////////
        public function details($id)
    {
        $book = Book::with(['category', 'comprobantes' => function($query) {
            $query->where('estado', 'activo');
        }])->findOrFail($id);

        return view('admin.search.details', compact('book'));
    }
    public function advanced()
    {
        $category = Category::all();
        $data = Book::paginate(15);

        return view('admin.search.advanced', compact('data', 'category'));
    }
    public function searchh(Request $request, $filter = null)
    {
        try {
            Log::info('Búsqueda de administrador iniciada', [
                'parámetros' => $request->all()
            ]);

            $category = Category::all();
            $query = Book::with('category');

            $searchTerm = $request->input('searchh') ?? $filter;

            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('N_codigo', '=', $searchTerm)  // Búsqueda exacta
                      ->orWhere('N_codigo', 'LIKE', "{$searchTerm}%") // Comienza con
                      ->orWhere('N_codigo', 'LIKE', "%{$searchTerm}") // Termina con
                      ->orWhere('N_codigo', 'LIKE', "%{$searchTerm}%") // Contiene
                      ->orWhere('ubicacion', '=', $searchTerm)  // Búsqueda exacta
                      ->orWhere('ubicacion', 'LIKE', "{$searchTerm}%") // Comienza con
                      ->orWhere('ubicacion', 'LIKE', "%{$searchTerm}") // Termina con
                      ->orWhere('ubicacion', 'LIKE', "%{$searchTerm}%") // Contiene
                      ->orWhere('year', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('tomo', 'LIKE', "%{$searchTerm}%");
                });
            }

            if ($request->filled('codigo')) {
                $codigo = $request->codigo;
                $query->where(function($q) use ($codigo) {
                    $q->where('N_codigo', '=', $codigo)  // Búsqueda exacta
                      ->orWhere('N_codigo', 'LIKE', "{$codigo}%") // Comienza con
                      ->orWhere('N_codigo', 'LIKE', "%{$codigo}") // Termina con
                      ->orWhere('N_codigo', 'LIKE', "%{$codigo}%"); // Contiene
                });
            }

            if ($request->filled('title')) {
                $query->where('title', 'LIKE', "%{$request->title}%");
            }

            if ($request->filled('ubicacion')) {
                $ubicacion = $request->ubicacion;
                $query->where(function($q) use ($ubicacion) {
                    $q->where('ubicacion', '=', $ubicacion)  // Búsqueda exacta
                      ->orWhere('ubicacion', 'LIKE', "{$ubicacion}%") // Comienza con
                      ->orWhere('ubicacion', 'LIKE', "%{$ubicacion}") // Termina con
                      ->orWhere('ubicacion', 'LIKE', "%{$ubicacion}%"); // Contiene
                });
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('tomo')) {
                $query->where('tomo', 'LIKE', "%{$request->tomo}%");
            }

            if ($request->filled('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            $orderBy = $request->input('order_by', 'created_at');
            $orderDir = $request->input('order_dir', 'desc');
            $query->orderBy($orderBy, $orderDir);

            $data = $query->paginate(15);
            $data->appends($request->all());

            return view('admin.search.advanced', compact('data', 'category'));

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de administrador: ' . $e->getMessage());
            return redirect()->route('search.advanced')
                ->with('error', 'Error al realizar la búsqueda: ' . $e->getMessage());
        }
    }
    public function cat_search($id)
    {
        $category = Category::all();

        $data = Book::where('category_id',$id)->get();

        return view('admin.search.advanced',compact('data','category'));
    }
}
