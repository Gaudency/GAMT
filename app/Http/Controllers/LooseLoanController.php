<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loose_Loan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LooseLoanController extends Controller
{
    public function index()
    {
        // Lista todos los préstamos sueltos
        $loans = Loose_Loan::all();
        return view('admin.loose_loans.index', compact('loans'));
    }

    public function create()
    {
        // Vista para crear un nuevo préstamo suelto
        return view('admin.loose_loans.create');
    }

    public function store(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'folder_code' => 'required|string|max:255',
            'book_title' => 'required|string|max:255',
            'sheets_count' => 'required|integer|min:1',
            'lender_name' => 'required|string|max:255',
            'loan_date' => 'required|date_format:Y-m-d\TH:i',
            'return_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:loan_date',
            'description' => 'nullable|string',
            'digital_signature' => 'nullable|string', // Firma digital opcional
            'terms_confirmation' => 'required|accepted', // Checkbox obligatorio
        ]);

        // Añadir log para depuración
        Log::info('Datos de préstamo recibidos:', [
            'tiene_firma' => $request->has('digital_signature') && !empty($request->digital_signature),
            'longitud_firma' => $request->has('digital_signature') ? strlen($request->digital_signature) : 0,
            'confirmacion_terminos' => $request->has('terms_confirmation') ? 'sí' : 'no',
            'fecha_prestamo' => $request->loan_date,
            'fecha_devolucion' => $request->return_date
        ]);

        try {
            // Procesamos los datos
            $loanData = [
                'folder_code' => $request->folder_code,
                'book_title' => $request->book_title,
                'sheets_count' => $request->sheets_count,
                'lender_name' => $request->lender_name,
                'loan_date' => Carbon::createFromFormat('Y-m-d\TH:i', $request->loan_date)->format('Y-m-d H:i:s'),
                'return_date' => Carbon::createFromFormat('Y-m-d\TH:i', $request->return_date)->format('Y-m-d H:i:s'),
                'description' => $request->description,
                'status' => 'loaned', // Por defecto está prestado
            ];

            // Solo agregamos la firma si realmente tiene contenido
            if ($request->filled('digital_signature')) {
                $loanData['digital_signature'] = $request->digital_signature;
            }

            // Crea el préstamo suelto
            $loan = Loose_Loan::create($loanData);

            // Log después de guardar
            Log::info('Préstamo guardado:', [
                'id' => $loan->id,
                'tiene_firma_guardada' => !empty($loan->digital_signature),
                'confirmacion_usada' => $request->has('terms_confirmation'),
                'fecha_prestamo_guardada' => $loan->loan_date,
                'fecha_devolucion_guardada' => $loan->return_date
            ]);

            return redirect()->route('loose-loans.confirmation', $loan->id)
                ->with('success', 'Préstamo suelto registrado exitosamente.');
        } catch (\Exception $e) {
            // Log de error
            Log::error('Error al guardar préstamo:', [
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->with('error', 'Error al guardar el préstamo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Encuentra el préstamo suelto
        $loan = Loose_Loan::findOrFail($id);
        return view('admin.loose_loans.show', compact('loan'));
    }

    public function edit($id)
    {
        // Encuentra el préstamo suelto por ID
        $loan = Loose_Loan::findOrFail($id);
        return view('admin.loose_loans.edit', compact('loan'));
    }

    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'folder_code' => 'required|string|max:255',
            'book_title' => 'required|string|max:255',
            'sheets_count' => 'required|integer|min:1',
            'lender_name' => 'required|string|max:255',
            'loan_date' => 'required|date',
            'return_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:loan_date',
            'description' => 'nullable|string',
            'status' => 'required|in:loaned,returned',
            'digital_signature' => 'nullable|string', // Firma digital opcional
            'terms_confirmation' => 'required|accepted', // Checkbox obligatorio
        ]);

        // Encuentra el préstamo suelto
        $loan = Loose_Loan::findOrFail($id);

        // Actualiza los datos del préstamo
        $loanData = $request->except(['terms_confirmation']);

        // Formatear la fecha de devolución correctamente
        $loanData['return_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->return_date)->format('Y-m-d H:i:s');

        // Solo actualizar la firma si se envía una nueva
        if (empty($request->digital_signature)) {
            unset($loanData['digital_signature']);
        }

        // Actualizar el préstamo
        $loan->update($loanData);

        // Log de la actualización
        Log::info('Préstamo actualizado:', [
            'id' => $loan->id,
            'tiene_firma' => !empty($loan->digital_signature),
            'confirmacion_usada' => true,
            'fecha_prestamo' => $loan->loan_date,
            'fecha_devolucion' => $loan->return_date
        ]);

        return redirect()->route('loose-loans.index')
            ->with('success', 'Préstamo suelto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Encuentra el préstamo suelto
        $loan = Loose_Loan::findOrFail($id);

        // Verificar si el préstamo está devuelto
        if ($loan->status !== 'returned') {
            return redirect()->route('loose-loans.index')
                ->with('error', 'No se puede eliminar un préstamo que no ha sido devuelto. Primero debe marcarlo como devuelto.');
        }

        // Si está devuelto, proceder con la eliminación
        $loan->delete();

        return redirect()->route('loose-loans.index')
            ->with('success', 'Préstamo suelto eliminado exitosamente.');
    }

    public function confirmation($id)
    {
        $loan = Loose_Loan::find($id);

        if (!$loan) {
            return redirect()->back()->with('error', 'Préstamo suelto no encontrado.');
        }

        return view('admin.loose_loans.confirmation', compact('loan'));
    }

    public function return($id)
    {
        // Cambia el estado del préstamo a "devuelto"
        $loan = Loose_Loan::findOrFail($id);
        $loan->update(['status' => 'returned']);

        return redirect()->route('loose-loans.index')
            ->with('success', 'Préstamo marcado como devuelto exitosamente.');
    }
}
