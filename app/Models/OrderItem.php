<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
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
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Calculate subtotal based on quantity and unit price
     */
    public function calculateSubtotal(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Update subtotal based on current quantity and unit price
     */
    public function updateSubtotal(): void
    {
        $this->subtotal = $this->calculateSubtotal();
        $this->save();
    }

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that belongs to the order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
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
     * Boot method to automatically calculate subtotal
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            if (!$orderItem->subtotal) {
                $orderItem->subtotal = $orderItem->calculateSubtotal();
            }
        });

        static::updating(function ($orderItem) {
            if ($orderItem->isDirty(['quantity', 'unit_price']) && !$orderItem->isDirty('subtotal')) {
                $orderItem->subtotal = $orderItem->calculateSubtotal();
            }
        });
    }
}
