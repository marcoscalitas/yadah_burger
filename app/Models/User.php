<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'gender',
        'birthdate',
        'password',
        'role',
        'image_url',
        'user_status',
        'email_verified_at',
        'is_online',
        'last_login',
        'failed_login_attempts',
        'account_locked_until',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date',
            'last_login' => 'datetime',
            'account_locked_until' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'failed_login_attempts' => 'integer',
        ];
    }

    /**
     * Accessor para compatibilidade com código que usa $user->name
     */
    public function getNameAttribute(): string
    {
        return $this->fullname;
    }

    public function getShortName(): string
    {
        $names = explode(' ', trim($this->fullname));

        if (count($names) === 0) {
            return '';
        }

        return $names[0].' '.(count($names) > 1 ? end($names) : '');
    }

    public function getRoleLabel(): string
    {
        return match ([$this->gender, $this->role]) {
            ['M', 'admin'] => 'Administrador',
            ['M', 'staff'] => 'Funcionário',
            ['F', 'admin'] => 'Administradora',
            ['F', 'staff'] => 'Funcionária',
            default => ucfirst($this->role ?? '-')
        };
    }

    public function getAge(): ?int
    {
        if (! $this->birthdate) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

    public function getImageUrl(): string
    {
        if ($this->image_url && Storage::disk('public')->exists($this->image_url)) {
            return asset("storage/{$this->image_url}");
        }

        $avatar = ($this->gender === 'M' ? 'avatar-1.jpg' : 'avatar-3.jpg');

        return asset("admin/assets/images/user/{$avatar}");
    }

    public function getGender(): string
    {
        return match ($this->gender) {
            'M' => 'Masculino',
            'F' => 'Femenina',
            default => '-',
        };
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
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new AdminVerifyEmail);
    }

}
