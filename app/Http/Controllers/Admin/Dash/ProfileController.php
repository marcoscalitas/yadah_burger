<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UploadService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    private const ADMIN_DASH_PROFILE = 'admin.dash.profile.';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(auth('admin')->id());

        return view(self::ADMIN_DASH_PROFILE.'index', compact('user'));
    }

    public function edit()
    {
        $user = User::find(auth('admin')->id());

        return view(self::ADMIN_DASH_PROFILE.'edit', compact('user'));
    }

    public function update(Request $request)
    {
        $currentUser = getCurrentUser('admin');
        $request->merge(['phone' => preg_replace('/\D/', '', $request->input('phone'))]);

        $data = $request->validate([
            'phone' => ['required', 'string', 'size:9', 'regex:/^\d{9}$/'],
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|in:M,F',
            'birthdate' => 'required|date',
            'role' => 'required|in:admin,staff',
        ]);

        if ($data['role'] === 'staff' && $currentUser->role === 'admin') {
            return redirect()->back()->withErrors([
                'role' => 'Você não pode alterar seu própria função de admin para staff.',
            ])->withInput();
        }

        if ($currentUser->role === 'staff' && $data['role'] !== $currentUser->role) {
            return redirect()->back()->withErrors([
                'role' => 'Você não tem permissão para alterar roles.',
            ])->withInput();
        }

        $currentUser->update($data);

        return redirect()->route('admin.profile.index')->with('success', 'Perfil atualizado com sucesso.');
    }

    public function uploadPhoto(Request $request, UploadService $uploader)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:3072',
            ]);

            $fileData = $uploader->configure([
                'mimes' => ['jpg', 'jpeg', 'png'],
                'maxSizeMB' => 3,
                'folder' => 'admin/profile_photos',
                'disk' => 'public',
            ])->upload($request->file('photo'));

            // Salva o caminho da foto no usuário (usando o guard correto)
            $user = getCurrentUser('admin');
            $user->image_url = $fileData['path'];
            $user->save();

            return response()->json([
                'message' => 'Foto enviada com sucesso!',
                'url' => $fileData['url'],
            ]);

        } catch (\Throwable $e) {
            Log::error('Erro no upload de foto de perfil: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erro ao enviar foto: '.$e->getMessage(),
            ], 500);
        }
    }
}
