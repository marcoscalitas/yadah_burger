<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private const ADMIN_DASH_ORDERS = 'admin.dash.orders.';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product', 'createdBy', 'updatedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by delivery type
        if ($request->filled('delivery_type')) {
            if ($request->delivery_type === 'pickup') {
                $query->where('pickup_in_store', true);
            } elseif ($request->delivery_type === 'delivery') {
                $query->where('pickup_in_store', false);
            }
        }

        // Search by customer name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view(self::ADMIN_DASH_ORDERS.'index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::active()->with('category')->orderBy('name', 'asc')->get();

        return view(self::ADMIN_DASH_ORDERS.'create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem criar pedidos.');
        }

        $currentUser = getCurrentUser('admin');

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'pickup_in_store' => 'required|boolean',
            'address_1' => 'nullable|string|max:255|required_if:pickup_in_store,false',
            'address_2' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'tpa'])],
            'discount_amount' => 'nullable|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.whatsapp_message' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            $validatedProducts = [];

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                $unitPrice = $product->promotion_price ?? $product->price;
                $subtotal = $productData['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                $validatedProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'whatsapp_message' => $productData['whatsapp_message'] ?? null,
                    'created_by' => $currentUser->id,
                ];
            }

            // Create order
            $orderData = [
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_in_store' => $validated['pickup_in_store'],
                'address_1' => $validated['address_1'] ?? null,
                'address_2' => $validated['address_2'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'order_status' => Order::STATUS_PENDING,
                'created_by' => $currentUser->id,
            ];

            $order = Order::create($orderData);

            // Create order items
            foreach ($validatedProducts as $productData) {
                $productData['order_id'] = $order->id;
                OrderItem::create($productData);
            }

            DB::commit();

            Log::info('Pedido criado com sucesso', [
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'total_amount' => $order->total_amount,
                'created_by' => $currentUser->id,
            ]);

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pedido criado com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar pedido', [
                'error' => $e->getMessage(),
                'user_id' => $currentUser->id,
            ]);

            return back()->withInput()->with('error', 'Erro ao criar pedido. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['orderItems.product.category', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return view(self::ADMIN_DASH_ORDERS.'show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::with(['orderItems.product', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        if ($order->isCancelled()) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Não é possível editar um pedido cancelado.');
        }

        $products = Product::active()->with('category')->orderBy('name', 'asc')->get();

        return view(self::ADMIN_DASH_ORDERS.'edit', compact('order', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (! isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem editar pedidos.');
        }

        $currentUser = getCurrentUser('admin');
        $order = Order::findOrFail($id);

        if ($order->isCancelled()) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Não é possível editar um pedido cancelado.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'pickup_in_store' => 'required|boolean',
            'address_1' => 'nullable|string|max:255|required_if:pickup_in_store,false',
            'address_2' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'tpa'])],
            'discount_amount' => 'nullable|numeric|min:0',
            'order_status' => ['required', Rule::in(['p', 'st', 'c', 'd', 'x'])],
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.whatsapp_message' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            $validatedProducts = [];

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                $unitPrice = $product->promotion_price ?? $product->price;
                $subtotal = $productData['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                $validatedProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'whatsapp_message' => $productData['whatsapp_message'] ?? null,
                    'updated_by' => $currentUser->id,
                ];
            }

            // Update order
            $orderData = [
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_in_store' => $validated['pickup_in_store'],
                'address_1' => $validated['address_1'] ?? null,
                'address_2' => $validated['address_2'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'order_status' => $validated['order_status'],
                'updated_by' => $currentUser->id,
            ];

            $order->update($orderData);

            // Delete existing order items and create new ones
            $order->orderItems()->delete();

            foreach ($validatedProducts as $productData) {
                $productData['order_id'] = $order->id;
                $productData['created_by'] = $order->created_by;
                OrderItem::create($productData);
            }

            DB::commit();

            Log::info('Pedido atualizado com sucesso', [
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'total_amount' => $order->total_amount,
                'updated_by' => $currentUser->id,
            ]);

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pedido atualizado com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar pedido', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $currentUser->id,
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar pedido. Tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (! isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem remover pedidos.');
        }

        $order = Order::findOrFail($id);
        $customerName = $order->customer_name;

        try {
            DB::beginTransaction();

            // Delete order items first
            $order->orderItems()->delete();

            // Delete order
            $order->delete();

            DB::commit();

            Log::info('Pedido removido com sucesso', [
                'order_id' => $id,
                'customer_name' => $customerName,
                'deleted_by' => getCurrentUser('admin')->id,
            ]);

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pedido removido com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover pedido', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => getCurrentUser('admin')->id,
            ]);

            return back()->with('error', 'Erro ao remover pedido. Tente novamente.');
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, string $id)
    {
        if (! isAdmin()) {
            return abort(403, 'Acesso negado. Apenas administradores podem atualizar status de pedidos.');
        }

        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['p', 'st', 'c', 'd', 'x'])],
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $validated['order_status'],
            'updated_by' => getCurrentUser('admin')->id,
        ]);

        Log::info('Status do pedido atualizado', [
            'order_id' => $order->id,
            'new_status' => $validated['order_status'],
            'updated_by' => getCurrentUser('admin')->id,
        ]);

        return redirect()->back()
            ->with('success', 'Status do pedido atualizado com sucesso.');
    }
}
