<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private const ADMIN_DASH_USERS = 'admin.dash.users.';

    private const ENTITY = 'utilizadores';

    public function index()
    {
        $users = User::orderBy('id', 'desc')
            ->where('user_status', '!=', 'd')
            ->get();

        return view(self::ADMIN_DASH_USERS . 'index', compact('users'));
    }

    public function create()
    {
        return view(self::ADMIN_DASH_USERS . 'create');
    }

    public function store(Request $request, UploadService $uploader)
    {
        checkIfIsAdmin('criar', self::ENTITY);

        $currentUser = getCurrentUser('admin');
        $request->merge([
            'phone' => preg_replace('/\D/', '', $request->input('phone')),
        ]);

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => ['required', 'string', 'size:9', 'regex:/^\d{9}$/'],
            'gender' => 'required|in:M,F',
            'birthdate' => 'required|date|before:today',
            'role' => 'required|in:admin,staff',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    validatePassword($value, $fail);
                },
            ],
            'password_confirmation' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('photo')) {
            $validated['image_url'] = $this->handlePhotoUpload($request, $uploader);
        }

        unset($validated['photo']);

        $validated['created_by'] = $currentUser->id;
        $user = User::create($validated);

        // Enviar email de verificação automaticamente
        event(new Registered($user));

        Log::info('Utilizador criado com sucesso', [
            'user_id' => $user->id,
            'user_name' => $user->fullname,
            'created_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador criado com sucesso! Email de verificação enviado.');
    }

    public function edit(string $id)
    {
        $user = User::with(['createdBy', 'updatedBy'])->findOrFail($id);
        $currentUser = getCurrentUser('admin');

        // Email verification checks
        $isEmailVerifiable = $user instanceof MustVerifyEmail;
        $emailNotVerified = $isEmailVerifiable && ! $user->hasVerifiedEmail();
        $canManageUser = $currentUser->id !== $user->id;

        return view(self::ADMIN_DASH_USERS . 'edit', compact(
            'user',
            'isEmailVerifiable',
            'emailNotVerified',
            'canManageUser'
        ));
    }

    public function update(Request $request, string $id)
    {
        checkIfIsAdmin('editar', self::ENTITY);

        $user = User::findOrFail($id);
        $currentUser = getCurrentUser('admin');
        $request->merge([
            'phone' => preg_replace('/\D/', '', $request->input('phone')),
        ]);
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => ['required', 'string', 'size:9', 'regex:/^\d{9}$/'],
            'gender' => 'required|in:M,F',
            'birthdate' => 'required|date|before:today',
            'role' => 'required|in:admin,staff',
        ]);

        if ($this->isLastAdminDemotion($user, $validated)) {
            return back()->withErrors(['role' => 'Não é possível alterar o role do último administrador.'])->withInput();
        }

        if ($currentUser->id == $user->id && $user->role !== $validated['role']) {
            return back()->withErrors(['role' => 'Não é possível alterar a sua própria função.'])->withInput();
        }

        $validated['updated_by'] = $currentUser->id;

        // Update email processing with verification using helper
        $successMessage = handleUserEmailUpdate($user, $validated, false, $currentUser->id);

        Log::info('Utilizador atualizado com sucesso', [
            'user_id' => $user->id,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', $successMessage);
    }

    public function uploadPhoto(Request $request, UploadService $uploader, string $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $user = User::findOrFail($id);
        $user->image_url = $this->handlePhotoUpload($request, $uploader);
        $user->save();

        return response()->json([
            'message' => 'Foto enviada com sucesso!',
            'url' => Storage::url($user->image_url),
        ]);
    }

    public function destroy(string $id)
    {
        checkIfIsAdmin('apagar', self::ENTITY);

        $user = User::findOrFail($id);
        $currentUser = getCurrentUser('admin');

        if ($currentUser->id == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível eliminar a sua própria conta.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível eliminar o último administrador do sistema.');
        }

        $user->update(['user_status' => 'd']);
        $user->delete();

        Log::info('Utilizador eliminado com sucesso', [
            'user_id' => $id,
            'deleted_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador eliminado com sucesso!');
    }

    /**
     * Display a listing of trashed users.
     */
    public function trashed()
    {
        checkIfIsAdmin('visualizar', self::ENTITY);

        $users = User::where('user_status', 'd')->orderBy('id', 'desc')->get();

        return view('admin.dash.users.trashed', compact('users'));
    }


    /**
     * Restore the specified user from trash.
     */
    public function restore(string $id)
    {
        checkIfIsAdmin('restaurar', self::ENTITY);

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        $user->update(['user_status' => 'a']);

        Log::info('Utilizador restaurado com sucesso', [
            'user_id' => $id,
            'restored_by' => getCurrentUser('admin')->id,
        ]);

        return redirect()->route('admin.users.trashed')
            ->with('success', 'Utilizador restaurado com sucesso!');
    }

    /**
     * Permanently delete the specified user.
     */
    public function forceDestroy(string $id)
    {
        checkIfIsAdmin('apagar_permanente', self::ENTITY);

        $user = User::onlyTrashed()->findOrFail($id);
        $currentUser = getCurrentUser('admin');

        if ($currentUser->id == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível eliminar permanentemente a sua própria conta.');
        }

        if ($user->image_url && fileExists($user->image_url)) {
            Storage::disk('public')->delete($user->image_url);
        }

        $user->forceDelete();

        Log::info('Utilizador eliminado permanentemente', [
            'user_id' => $id,
            'force_deleted_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.users.trashed')
            ->with('success', 'Utilizador eliminado permanentemente!');
    }

    /**
     * Reset user password
     */
    public function resetPassword(string $id)
    {
        checkIfIsAdmin('editar', self::ENTITY);

        $user = User::findOrFail($id);
        $currentUser = getCurrentUser('admin');

        if ($currentUser->id == $user->id) {
            return back()->withErrors(['error' => 'Não é possível resetar a sua própria senha.'])->withInput();
        }

        // Generate password based on user role
        $temporaryPassword = 'Yadah123#';

        $user->update([
            'password' => Hash::make($temporaryPassword),
            'updated_by' => $currentUser->id,
        ]);

        Log::info('Senha do utilizador resetada', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'reset_by' => $currentUser->id,
        ]);

        return back()->with('success', 'Senha resetada com sucesso! Nova senha temporária: ' . $temporaryPassword . '. O utilizador deve alterar esta senha no próximo login.');
    }

    /**
     * Send verification email to user
     */
    public function sendVerificationEmail(string $id)
    {
        checkIfIsAdmin('editar', self::ENTITY);

        $user = User::findOrFail($id);
        $currentUser = getCurrentUser('admin');

        if ($currentUser->id == $user->id) {
            return back()->withErrors(['error' => 'Não é possível enviar email de verificação para a sua própria conta.'])->withInput();
        }

        // Check if user is email verifiable
        if (! $user instanceof MustVerifyEmail) {
            return back()->withErrors(['error' => 'Este utilizador não requer verificação de email.'])->withInput();
        }

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return back()->withErrors(['error' => 'O email deste utilizador já está verificado.'])->withInput();
        }

        try {
            // Send verification email
            $user->sendEmailVerificationNotification();

            Log::info('Email de verificação enviado', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'sent_by' => $currentUser->id,
            ]);

            return back()->with('success', 'Email de verificação enviado com sucesso para: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de verificação', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Erro ao enviar email de verificação. Tente novamente.'])->withInput();
        }
    }

    /** ------------------------------
     * Helpers (código reutilizável)
     * ------------------------------ */
    private function handlePhotoUpload(Request $request, UploadService $uploader): string
    {
        try {
            $fileData = $uploader->configure([
                'mimes' => ['jpg', 'jpeg', 'png'],
                'maxSizeMB' => 3,
                'folder' => 'admin/users/profile_photos',
                'disk' => 'public',
            ])->upload($request->file('photo'));

            return $fileData['path'];
        } catch (\Throwable $e) {
            Log::error('Erro no upload da foto: ' . $e->getMessage());
            throw new \Exception('Erro ao enviar foto: ' . $e->getMessage());
        }
    }

    private function isLastAdminDemotion(User $user, array $data): bool
    {
        return $user->role === 'admin'
            && $data['role'] !== 'admin'
            && User::where('role', 'admin')->count() <= 1;
    }
}
