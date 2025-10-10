<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private const ADMIN_DASH_CATEGORIES = 'admin.dash.categories.';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view(self::ADMIN_DASH_CATEGORIES . 'index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::ADMIN_DASH_CATEGORIES . 'create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem criar categorias.');
        }

        $currentUser = getCurrentUser();
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = handlePhotoUpload($request, 'admin/categories/images', 'image');
        }

        unset($validated['image']);

        $category = Category::create($validated);

        Log::info('Categoria criada com sucesso', [
            'category_id'   => $category->id,
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
        $category = Category::findOrFail($id);
        return view(self::ADMIN_DASH_CATEGORIES . 'edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem editar categorias.');
        }

        $currentUser = getCurrentUser();
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = handlePhotoUpload($request, 'admin/categories/images', 'image');
        }

        unset($validated['image']);

        $category->update($validated);

        Log::info('Categoria editada com sucesso', [
            'category_id'   => $category->id,
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
        if (!isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem apagar categorias.');
        }

        $currentUser = getCurrentUser();
        $category = Category::findOrFail($id);
        $category->delete();

        Log::info('Categoria apagada com sucesso', [
            'category_id'   => $category->id,
            'category_name' => $category->name,
            'deleted_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria apagada com sucesso.');
    }
}
