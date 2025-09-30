<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'is_online',
        'last_login',
        'failed_login_attempts',
        'account_locked_until',
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
            'last_login' => 'datetime',
            'account_locked_until' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'failed_login_attempts' => 'integer',
        ];
    }

    public function getShortName(): string
    {
        $names = explode(' ', trim($this->fullname));

        if (count($names) === 0) {
            return '';
        }

        return $names[0] . ' ' . (count($names) > 1 ? end($names) : '');
    }

    public function getRoleLabel(): string
    {
        return match ([$this->gender, $this->role]) {
            ['M', 'admin'] => 'Administrador',
            ['M', 'staff']  => 'Funcionário',
            ['F', 'admin'] => 'Administradora',
            ['F', 'staff']  => 'Funcionária',
        };
    }

    public function getFormattedPhone($code = true): string
    {
        $digits = preg_replace('/\D/', '', $this->phone);

        if (strlen($digits) !== 9) {
            return $this->phone;
        }

        $part1 = substr($digits, 0, 3);
        $part2 = substr($digits, 3, 3);
        $part3 = substr($digits, 6, 3);

        return ($code) ? "(+244) {$part1}-{$part2}-{$part3}" : "{$part1}-{$part2}-{$part3}";
    }

    public function getAge(): ?int
    {
        if (!$this->birthdate) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

    public function statusShort(): ?string
    {
        return match ($this->user_status) {
            'p' => 'Pendente',
            'a' => 'Ativo',
            'sp' => 'Suspenso',
            'b' => 'Banido',
            default => null,
        };
    }

    public function getFormattedDate(string $field, string $format = 'd-m-Y'): ?string
    {
        if (!isset($this->$field) || !$this->$field) {
            return null;
        }

        $value = $this->$field;

        if ($value instanceof Carbon) {
            $date = $value;
        } else {
            try {
                $date = Carbon::parse($value);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $date->format($format);
    }

    public function getImageUrl(): string
    {
        if ($this->image_url && Storage::disk('public')->exists($this->image_url)) {
            return asset("storage/{$this->image_url}");
        }

        $avatar = ($this->gender === 'M' ? 'avatar-1.jpg' : 'avatar-3.jpg');
        return asset("admin/assets/images/user/{$avatar}");
    }
}
