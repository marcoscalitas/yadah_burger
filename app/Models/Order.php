<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'pickup_in_store',
        'address_1',
        'address_2',
        'notes',
        'payment_method',
        'subtotal',
        'total_amount',
        'discount_amount',
        'whatsapp_message',
        'order_status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pickup_in_store' => 'boolean',
            'subtotal' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'p';      // Pendente
    public const STATUS_STARTED = 'st';     // Iniciado
    public const STATUS_COMPLETED = 'c';    // Concluído
    public const STATUS_DELIVERED = 'd';    // Entregue
    public const STATUS_CANCELLED = 'x';    // Cancelado

    /**
     * Get status name in Portuguese
     */
    public function getStatusName(): string
    {
        return match ($this->order_status) {
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_STARTED => 'Iniciado',
            self::STATUS_COMPLETED => 'Concluído',
            self::STATUS_DELIVERED => 'Entregue',
            self::STATUS_CANCELLED => 'Cancelado',
            default => 'Desconhecido',
        };
    }

    /**
     * Get payment method name in Portuguese
     */
    public function getPaymentMethodName(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Dinheiro',
            'transfer' => 'Transferência',
            'tpa' => 'TPA',
            default => 'Desconhecido',
        };
    }

    /**
     * Get total amount after discount
     */
    public function getFinalAmount(): float
    {
        return $this->total_amount - $this->discount_amount;
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, [self::STATUS_PENDING, self::STATUS_STARTED]);
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->order_status === self::STATUS_COMPLETED;
    }

    /**
     * Check if order is delivered
     */
    public function isDelivered(): bool
    {
        return $this->order_status === self::STATUS_DELIVERED;
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->order_status === self::STATUS_CANCELLED;
    }

    /**
     * Scope to get orders by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('order_status', $status);
    }

    /**
     * Scope to get pending orders
     */
    public function scopePending($query)
    {
        return $query->where('order_status', self::STATUS_PENDING);
    }

    /**
     * Scope to get active orders (not cancelled)
     */
    public function scopeActive($query)
    {
        return $query->where('order_status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope to get pickup orders
     */
    public function scopePickup($query)
    {
        return $query->where('pickup_in_store', true);
    }

    /**
     * Scope to get delivery orders
     */
    public function scopeDelivery($query)
    {
        return $query->where('pickup_in_store', false);
    }

    /**
     * Get the order items relationship
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the products through order items
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'unit_price', 'subtotal', 'whatsapp_message'])
            ->withTimestamps();
    }

    /**
     * Relacionamento com o usuário que criou este registro
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com o usuário que atualizou este registro pela última vez
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Gera o link do WhatsApp para este pedido
     * Usa a mensagem armazenada para evitar queries desnecessárias
     */
    public function getWhatsAppLink(): string
    {
        // Número da empresa Yadah Burguer
        $businessPhone = config('app.whatsapp_business_number');
        // Remove caracteres não numéricos
        $businessPhone = preg_replace('/\D/', '', $businessPhone);

        // Se não começar com código do país, adiciona 244 (Angola)
        if (strlen($businessPhone) === 9 && !str_starts_with($businessPhone, '244')) {
            $businessPhone = '244' . $businessPhone;
        }

        // Usa a mensagem armazenada ao invés de gerar novamente
        $message = $this->whatsapp_message ?? $this->getWhatsAppMessage();

        return "https://wa.me/{$businessPhone}?text=" . urlencode($message);
    }

    /**
     * Gera a mensagem do WhatsApp para este pedido
     * Este método só deve ser usado internamente quando a mensagem precisa ser regenerada
     */
    public function getWhatsAppMessage(): string
    {
        $whatsappService = new \App\Services\WhatsAppService();
        return $whatsappService->generateOrderMessage($this);
    }

    /**
     * Atualiza a mensagem do WhatsApp armazenada
     * Deve ser chamado sempre que os dados do pedido mudarem
     */
    public function updateWhatsAppMessage(): void
    {
        $this->whatsapp_message = $this->getWhatsAppMessage();
        $this->saveQuietly(); // Salva sem disparar eventos
    }
}
