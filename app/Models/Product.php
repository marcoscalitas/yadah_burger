<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'promotion_price',
        'image_url',
        'product_status',
        'is_featured',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'promotion_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the product image URL.
     */
    public function getImageUrl(): string
    {
        if ($this->image_url && Storage::disk('public')->exists($this->image_url)) {
            return asset("storage/{$this->image_url}");
        }
        // Default product image
        return asset('admin/assets/images/product/default-product.jpg');
    }

    /**
     * Get a truncated description for display in lists.
     */
    public function getShortDescription(int $length = 50): string
    {
        if (!$this->description) {
            return '';
        }
        return strlen($this->description) > $length
            ? substr($this->description, 0, $length) . '...'
            : $this->description;
    }

    /**
     * Scope to get only active products.
     */
    public function scopeActive($query)
    {
        return $query->where('product_status', 'active');
    }

    /**
     * Scope to get only inactive products.
     */
    public function scopeInactive($query)
    {
        return $query->where('product_status', 'inactive');
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
