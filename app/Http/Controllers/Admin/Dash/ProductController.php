<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private const ADMIN_DASH_PRODUCTS = 'admin.dash.products.';

    private const ENTITY = 'produtos';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        return view(self::ADMIN_DASH_PRODUCTS.'index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view(self::ADMIN_DASH_PRODUCTS.'create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        [$validated, $currentUser] = $this->handleSubmit($request);
        $validated['created_by'] = $currentUser->id;
        $product = Product::create($validated);

        Log::info('Produto criado com sucesso', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'created_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with(['category', 'createdBy', 'updatedBy'])->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();

        return view(self::ADMIN_DASH_PRODUCTS.'edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        [$validated, $currentUser] = $this->handleSubmit($request, true);
        $validated['updated_by'] = $currentUser->id;
        $product->update($validated);

        Log::info('Produto atualizado com sucesso', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        checkIfIsAdmin('apagar', self::ENTITY);

        $product = Product::findOrFail($id);
        $productName = $product->name;
        $product->delete();

        Log::info('Produto removido com sucesso', [
            'product_id' => $id,
            'product_name' => $productName,
            'deleted_by' => getCurrentUser('admin')->id,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto removido com sucesso.');
    }

    /** ------------------------------
     * Helpers (Reusable code)
     * ------------------------------ */
    private function handleSubmit(Request $request, bool $isUpdate = false)
    {
        checkIfIsAdmin($isUpdate ? 'editar' : 'criar', self::ENTITY);

        $currentUser = getCurrentUser('admin');
        $isPromotion = $request->boolean('is_featured');

        $request->merge([
            'price' => $this->convertToDecimal($request->input('price') ?? 0),
            'promotion_price' => $isPromotion
                ? $this->convertToDecimal($request->input('promotion_price'))
                : null,
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|gt:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'is_featured' => 'required|boolean',
        ];

        $rules['promotion_price'] = $isPromotion
            ? 'required|numeric|gt:0'
            : 'nullable|numeric|gte:0';

        $validated = $request->validate($rules);

        if ($request->hasFile('image')) {
            $validated['image_url'] = handlePhotoUpload($request, 'admin/products/images', 'image');
        }

        unset($validated['image']);

        return [$validated, $currentUser];
    }

    /**
     * Converte uma string de preço formatada (ex: "50.000,00") para decimal (ex: "50000.00").
     */
    private function convertToDecimal($value)
    {

        // Remove pontos de milhar
        $value = str_replace('.', '', $value);
        // Troca vírgula decimal por ponto
        $value = str_replace(',', '.', $value);

        // Garante que é numérico
        return is_numeric($value) ? (float) $value : 0;
    }
}
