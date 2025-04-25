<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get(); // Obtener usuarios ordenados por más recientes
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create'); // Formulario de creación
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255|regex:/^[a-zA-Z0-9_.@-]+$/',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'usertype' => 'required|string|in:user,admin',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6144',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'phone.unique' => 'Este número de teléfono ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'usertype.required' => 'El tipo de usuario es obligatorio',
            'profile_photo_path.image' => 'El archivo debe ser una imagen',
            'profile_photo_path.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'profile_photo_path.max' => 'La imagen no debe ser mayor a 6MB',
            'username.regex' => 'El usuario o email solo puede contener letras, números y los caracteres _.@-',
        ]);

        // Crear el usuario primero sin la foto
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'usertype' => $request->usertype,
        ]);

        // Manejar la carga de la foto de perfil si existe
        if ($request->hasFile('profile_photo_path')) {
            $this->updateProfilePhoto($user, $request->file('profile_photo_path'));
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:255|regex:/^[a-zA-Z0-9_.@-]+$/',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'usertype' => 'required|string|in:user,admin',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6144',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'phone.unique' => 'Este número de teléfono ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'usertype.required' => 'El tipo de usuario es obligatorio',
            'profile_photo_path.image' => 'El archivo debe ser una imagen',
            'profile_photo_path.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'profile_photo_path.max' => 'La imagen no debe ser mayor a 6MB',
            'username.regex' => 'El usuario o email solo puede contener letras, números y los caracteres _.@-',
        ]);

        // Actualizar datos básicos
        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'usertype' => $request->usertype,
        ];

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Manejar la actualización de la foto de perfil si existe
        if ($request->hasFile('profile_photo_path')) {
            $this->updateProfilePhoto($user, $request->file('profile_photo_path'));
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }
    public function destroy(User $user)
    {
        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Obtener documentos asociados al usuario
            $documents = \App\Models\Document::where('user_id', $user->id)->get();

            // En lugar de eliminar los documentos, actualizamos el user_id a NULL
            // o a un usuario de sistema (por ejemplo, usuario con ID 1 que puede ser el superadmin)
            foreach ($documents as $document) {
                $document->user_id = null; // O asignar a un usuario sistema, ej: 1
                $document->save();
            }

            // Eliminar la foto de perfil si existe
            $this->deleteProfilePhoto($user);

            // Ahora eliminar el usuario
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente. Los registros de préstamos se han mantenido en el sistema.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Método para actualizar la foto de perfil directamente en public/images/profile-photos
     * (Consistente con PerfilController)
     */
    private function updateProfilePhoto(User $user, $photo)
    {
        $oldPath = $user->profile_photo_path; // Ruta relativa antigua (puede ser de storage o public)

        // Generar nombre único y definir ruta dentro de public/
        $filename = time() . '_' . Str::slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $photo->getClientOriginalExtension();
        $relativePath = 'images/profile-photos'; // Ruta relativa dentro de public/
        $publicPath = public_path($relativePath); // Ruta absoluta en el servidor
        $newDbPath = $relativePath . '/' . $filename; // Ruta a guardar en DB

        // Crear directorio si no existe
        if (!File::isDirectory($publicPath)) {
            File::makeDirectory($publicPath, 0775, true, true);
        }

        // Mover el nuevo archivo
        $photo->move($publicPath, $filename);

        // Actualizar la ruta en la base de datos
        $user->forceFill([
            'profile_photo_path' => $newDbPath,
        ])->save();

        // Intentar eliminar foto antigua si era diferente y estaba en public/images...
        if ($oldPath && Str::startsWith($oldPath, 'images/profile-photos/') && File::exists(public_path($oldPath)) && public_path($oldPath) !== public_path($newDbPath)) {
             File::delete(public_path($oldPath));
        }
        // O si estaba en storage/app/public/profile-photos...
        elseif ($oldPath && Str::startsWith($oldPath, 'profile-photos/') && Storage::disk('public')->exists($oldPath)) {
             Storage::disk('public')->delete($oldPath);
        }
    }

    /**
     * Método para eliminar la foto de perfil directamente desde public/images/profile-photos
     * (Consistente con PerfilController)
     */
    private function deleteProfilePhoto(User $user)
    {
        $currentPhotoPath = $user->profile_photo_path; // Ruta relativa (podría ser de storage o public)

        // Intentar eliminar foto (puede estar en public/images...)
        if ($currentPhotoPath && Str::startsWith($currentPhotoPath, 'images/profile-photos/') && File::exists(public_path($currentPhotoPath))) {
            File::delete(public_path($currentPhotoPath));
        }
        // O si estaba en storage/app/public/profile-photos...
        elseif ($currentPhotoPath && Str::startsWith($currentPhotoPath, 'profile-photos/') && Storage::disk('public')->exists($currentPhotoPath)) {
             Storage::disk('public')->delete($currentPhotoPath);
        }

        // Limpiar la ruta en la base de datos
        if ($user->profile_photo_path !== null) {
            $user->forceFill([
                'profile_photo_path' => null,
            ])->save();
        }
    }
}

