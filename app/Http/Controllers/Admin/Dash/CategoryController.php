<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private const ADMIN_DASH_CATEGORIES = 'admin.dash.categories.';

    private const ENTITY = 'categorias';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();

        return view(self::ADMIN_DASH_CATEGORIES.'index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::ADMIN_DASH_CATEGORIES.'create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        [$validated, $currentUser] = $this->handleSubmit($request);
        $validated['created_by'] = $currentUser->id;
        $category = Category::create($validated);

        Log::info('Categoria criada com sucesso', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'created_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::with(['createdBy', 'updatedBy'])->findOrFail($id);

        return view(self::ADMIN_DASH_CATEGORIES.'edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        [$validated, $currentUser] = $this->handleSubmit($request, true);
        $validated['updated_by'] = $currentUser->id;
        $category->update($validated);

        Log::info('Categoria editada com sucesso', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria editada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        checkIfIsAdmin('apagar', self::ENTITY);

        $currentUser = getCurrentUser('admin');

        $category = Category::findOrFail($id);
        $category->update(['category_status' => 'd']);
        $category->delete();

        Log::info('Categoria apagada com sucesso', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'deleted_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria apagada com sucesso.');
    }

    /**
     * Display a listing of trashed categories.
     */
    public function trashed()
    {
        checkIfIsAdmin('visualizar', self::ENTITY);

        $categories = Category::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('admin.dash.categories.trashed', compact('categories'));
    }

    /**
     * Restore the specified category from trash.
     */
    public function restore(string $id)
    {
        checkIfIsAdmin('restaurar', self::ENTITY);

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        $category->update(['category_status' => 'a']);

        Log::info('Categoria restaurada com sucesso', [
            'category_id' => $id,
            'category_name' => $category->name,
            'restored_by' => getCurrentUser('admin')->id,
        ]);

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Categoria restaurada com sucesso.');
    }

    /**
     * Permanently delete the specified category.
     */
    public function forceDestroy(string $id)
    {
        checkIfIsAdmin('apagar_permanente', self::ENTITY);

        $category = Category::onlyTrashed()->findOrFail($id);
        $currentUser = getCurrentUser('admin');

        if ($category->image_url && fileExists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }

        $categoryName = $category->name;
        $category->forceDelete();

        Log::info('Categoria eliminada permanentemente', [
            'category_id' => $id,
            'category_name' => $categoryName,
            'force_deleted_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Categoria eliminada permanentemente.');
    }

    /** ------------------------------
     * Helpers (Reusable code)
     * ------------------------------ */
    private function handleSubmit(Request $request, bool $isUpdate = false)
    {
        checkIfIsAdmin($isUpdate ? 'editar' : 'criar', self::ENTITY);

        $currentUser = getCurrentUser('admin');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = handlePhotoUpload($request, 'admin/categories/images', 'image');
        }

        unset($validated['image']);

        return [$validated, $currentUser];
    }
}
