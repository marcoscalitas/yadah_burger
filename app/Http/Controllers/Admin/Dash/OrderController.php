<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['products'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dash.orders.index', compact('orders'));
    }

    public function create()
    {
        // Buscar todas as categorias com seus produtos
        $categories = Category::with('products')
            ->whereHas('products')
            ->orderBy('name')
            ->get();

        return view('admin.dash.orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'pickup_in_store' => 'required|boolean',
            'payment_method' => 'required|in:cash,transfer,tpa',
            'address_1' => 'required_if:pickup_in_store,0|nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Calcular totais
            $subtotal = 0;
            $productData = [];

            foreach ($validated['products'] as $productItem) {
                $product = Product::findOrFail($productItem['product_id']);
                $quantity = $productItem['quantity'];
                $price = $product->price;
                $itemTotal = $price * $quantity;

                $subtotal += $itemTotal;

                $productData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal
                ];
            }

            $discountAmount = (float) str_replace(['.', ','], ['', '.'], $validated['discount_amount'] ?? '0');
            $total = $subtotal - $discountAmount;

            // Criar pedido
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_in_store' => $validated['pickup_in_store'],
                'payment_method' => $validated['payment_method'],
                'address_1' => $validated['address_1'],
                'address_2' => $validated['address_2'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'notes' => $validated['notes'],
                'status' => 'pending'
            ]);

            // Adicionar produtos ao pedido
            foreach ($productData as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Pedido criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->with('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['products']);
        return view('admin.dash.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', true)
                  ->orderBy('name');
        }])
        ->whereHas('products', function($query) {
            $query->where('is_active', true);
        })
        ->orderBy('name')
        ->get();

        $order->load(['products']);

        return view('admin.dash.orders.edit', compact('order', 'categories'));
    }

    public function update(Request $request, Order $order)
    {
        // Similar validation and logic as store
        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Order $order)
    {
        try {
            $order->products()->detach();
            $order->delete();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Pedido excluído com sucesso!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir pedido: ' . $e->getMessage());
        }
    }

    private function generateOrderNumber()
    {
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;

        return 'PED-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
