<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'category_status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the category image URL.
     */
    public function getImageUrl(): string
    {
        if ($this->image_url && Storage::disk('public')->exists($this->image_url)) {
            return asset("storage/{$this->image_url}");
        }

        // Default category image
        return asset('admin/assets/images/category/default-category.jpg');
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
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('category_status', 'a');
    }

    /**
     * Scope to get only inactive categories.
     */
    public function scopeInactive($query)
    {
        return $query->where('category_status', 'i');
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
}
