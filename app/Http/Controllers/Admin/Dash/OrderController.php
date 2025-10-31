<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    private const ENTITY = 'Pedidos';

    public function index()
    {
        $orders = Order::with(['orderItems.product', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dash.orders.index', compact('orders'));
    }

    public function create()
    {
        // Buscar todas as categorias com seus produtos ativos
        $categories = Category::with(['products' => function ($query) {
            $query->where('product_status', 'a')
                ->orderBy('name');
        }])
            ->whereHas('products', function ($query) {
                $query->where('product_status', 'a');
            })
            ->orderBy('name')
            ->get();

        return view('admin.dash.orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        checkIfIsAdmin('criar', self::ENTITY);

        $currentUser = getCurrentUser('admin');
        $request->merge([
            'customer_phone' => preg_replace('/\D/', '', $request->input('customer_phone')),
        ]);

        if ($request->has('discount_amount') && $request->discount_amount) {
            $request->merge([
                'discount_amount' => convertToDecimal($request->discount_amount)
            ]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_in_store' => 'required|boolean',
            'payment_method' => 'required|in:cash,transfer,tpa',
            'address_1' => 'required_if:pickup_in_store,0|nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calcular totais e validar produtos
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['products'] as $productData) {
                // Busca o produto do banco para validar
                $product = Product::where('id', $productData['id'])
                    ->where('product_status', 'a')
                    ->first();

                if (!$product) {
                    throw new \Exception("Produto ID {$productData['id']} não está disponível.");
                }

                // Valida o preço (não confia no frontend)
                $priceFromDB = (float) $product->price;
                $priceFromRequest = (float) $productData['price'];

                if (abs($priceFromDB - $priceFromRequest) > 0.01) {
                    throw new \Exception("Preço do produto '{$product->name}' foi alterado. Recarregue a página.");
                }

                $quantity = (int) $productData['quantity'];
                $itemSubtotal = $priceFromDB * $quantity;

                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $priceFromDB,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calcula desconto e total
            $discountAmount = isset($validated['discount_amount'])
                ? (float) $validated['discount_amount']
                : 0;

            $totalAmount = max(0, $subtotal - $discountAmount);

            // Gera número do pedido
            $orderNumber = $this->generateOrderNumber();

            // Criar pedido
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_in_store' => $validated['pickup_in_store'],
                'payment_method' => $validated['payment_method'],
                'address_1' => $validated['address_1'],
                'address_2' => $validated['address_2'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
                'order_status' => Order::STATUS_PENDING,
                'created_by' => $currentUser->id,
            ]);

            // Adicionar items do pedido
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'created_by' => $currentUser->id,
                ]);
            }

            // Gera e armazena a mensagem do WhatsApp
            $whatsappService = new WhatsAppService();
            $order->refresh(); // Recarrega o pedido com os items
            $order->load(['orderItems.product']);
            $whatsappMessage = $whatsappService->generateOrderMessage($order);
            $order->update(['whatsapp_message' => $whatsappMessage]);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', ['order' => $order, 'created' => 1])
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
        $order->load(['orderItems.product', 'createdBy', 'updatedBy']);
        return view('admin.dash.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        checkIfIsAdmin('criar', self::ENTITY);

        // Não permite editar pedidos já concluídos ou entregues
        if (in_array($order->order_status, [Order::STATUS_COMPLETED, Order::STATUS_DELIVERED])) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Não é possível editar pedidos concluídos ou entregues.');
        }

        $categories = Category::with(['products' => function ($query) {
            $query->where('product_status', 'a')
                ->orderBy('name');
        }])
            ->whereHas('products', function ($query) {
                $query->where('product_status', 'a');
            })
            ->orderBy('name')
            ->get();

        $order->load(['orderItems.product.category']);

        // Prepara os produtos existentes para o JavaScript
        $existingProducts = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float) $item->unit_price,
                'category' => $item->product->category?->name ?? '',
                'image' => $item->product->image_url ? asset('storage/' . $item->product->image_url) : '',
                'quantity' => $item->quantity
            ];
        })->values();

        return view('admin.dash.orders.edit', compact('order', 'categories', 'existingProducts'));
    }

    public function update(Request $request, Order $order)
    {
        // Não permite editar pedidos já concluídos ou entregues
        if (in_array($order->order_status, [Order::STATUS_COMPLETED, Order::STATUS_DELIVERED])) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Não é possível editar pedidos concluídos ou entregues.');
        }

        // Normaliza o desconto usando o helper
        if ($request->has('discount_amount') && $request->discount_amount) {
            $request->merge([
                'discount_amount' => convertToDecimal($request->discount_amount)
            ]);
        }

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
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calcular totais e validar produtos
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['products'] as $productData) {
                $product = Product::where('id', $productData['id'])
                    ->where('product_status', 'a')
                    ->first();

                if (!$product) {
                    throw new \Exception("Produto ID {$productData['id']} não está disponível.");
                }

                $priceFromDB = (float) $product->price;
                $priceFromRequest = (float) $productData['price'];

                if (abs($priceFromDB - $priceFromRequest) > 0.01) {
                    throw new \Exception("Preço do produto '{$product->name}' foi alterado. Recarregue a página.");
                }

                $quantity = (int) $productData['quantity'];
                $itemSubtotal = $priceFromDB * $quantity;

                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $priceFromDB,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discountAmount = isset($validated['discount_amount'])
                ? (float) $validated['discount_amount']
                : 0;

            $totalAmount = max(0, $subtotal - $discountAmount);

            // Atualizar pedido
            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_in_store' => $validated['pickup_in_store'],
                'payment_method' => $validated['payment_method'],
                'address_1' => $validated['address_1'],
                'address_2' => $validated['address_2'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
                'updated_by' => Auth::id(),
            ]);

            // Remove items antigos e cria novos
            $order->orderItems()->delete();

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'created_by' => $order->created_by,
                    'updated_by' => Auth::id(),
                ]);
            }

            // Regenera a mensagem do WhatsApp com os dados atualizados
            $whatsappService = new WhatsAppService();
            $order->refresh(); // Recarrega o pedido com os novos items
            $order->load(['orderItems.product']);
            $whatsappMessage = $whatsappService->generateOrderMessage($order);
            $order->update(['whatsapp_message' => $whatsappMessage]);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Pedido atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar pedido: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        // Só permite excluir pedidos pendentes ou cancelados
        if (!in_array($order->order_status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED])) {
            return back()->with('error', 'Apenas pedidos pendentes ou cancelados podem ser excluídos.');
        }

        try {
            DB::beginTransaction();

            // Remove items do pedido
            $order->orderItems()->delete();

            // Remove o pedido (soft delete)
            $order->delete();

            DB::commit();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Pedido excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao excluir pedido: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:p,st,c,d,x',
        ]);

        try {
            $order->update([
                'order_status' => $validated['order_status'],
                'updated_by' => Auth::id(),
            ]);

            // Atualiza a mensagem do WhatsApp com o novo status
            $order->updateWhatsAppMessage();

            $statusName = $order->getStatusName();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', "Status do pedido atualizado para: {$statusName}");
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of trashed orders.
     */
    public function trashed()
    {
        checkIfIsAdmin('visualizar', self::ENTITY);

        $orders = Order::onlyTrashed()
            ->with(['orderItems.product', 'createdBy'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.dash.orders.trashed', compact('orders'));
    }

    /**
     * Restore the specified order from trash.
     */
    public function restore(string $id)
    {
        checkIfIsAdmin('restaurar', self::ENTITY);

        try {
            DB::beginTransaction();

            $order = Order::onlyTrashed()->findOrFail($id);

            // Restaura os items do pedido
            $order->orderItems()->withTrashed()->restore();

            // Restaura o pedido
            $order->restore();

            DB::commit();

            return redirect()
                ->route('admin.orders.trashed')
                ->with('success', 'Pedido restaurado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao restaurar pedido: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified order.
     */
    public function forceDestroy(string $id)
    {
        checkIfIsAdmin('apagar_permanente', self::ENTITY);

        try {
            DB::beginTransaction();

            $order = Order::onlyTrashed()->findOrFail($id);

            // Remove permanentemente os items do pedido
            $order->orderItems()->withTrashed()->forceDelete();

            // Remove permanentemente o pedido
            $order->forceDelete();

            DB::commit();

            return redirect()
                ->route('admin.orders.trashed')
                ->with('success', 'Pedido eliminado permanentemente!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao eliminar pedido: ' . $e->getMessage());
        }
    }

    private function generateOrderNumber()
    {
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;

        return 'PED-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
