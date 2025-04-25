<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.show', [
            'user' => Auth::user(),
        ]);
    }

    public function edit()
    {
        return view('perfil.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
        ]);

        return back()->with('status', 'perfil-actualizado');
    }

    /**
     * Update the authenticated user's profile photo using direct public storage.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:4096'],
        ]);

        $user = Auth::user();
        $photo = $request->file('photo');
        $oldPath = $user->profile_photo_path; // Ruta relativa antigua desde public/

        // Generar nombre único y definir ruta
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

        // Eliminar foto antigua si existía y es diferente
        if ($oldPath && File::exists(public_path($oldPath)) && public_path($oldPath) !== public_path($newDbPath)) {
            File::delete(public_path($oldPath));
        }

        return back()->with('status', 'photo-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $currentPhotoPath = $user->profile_photo_path;

        Auth::logout();

        // Eliminar usuario de la DB
        DB::table('users')->where('id', $user->id)->delete();

        // Invalidar sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Eliminar foto física DESPUÉS de eliminar el usuario y hacer logout
        if ($currentPhotoPath && File::exists(public_path($currentPhotoPath))) {
            File::delete(public_path($currentPhotoPath));
        }

        return redirect('/');
    }
}
