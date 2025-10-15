<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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

    public function getFormattedPhone($code = true): string
    {
        if (empty($this->phone)) {
            return '-';
        }

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
            default => 'Não especificado',
        };
    }

    public function getFormattedBirthdate(string $format = 'd-m-Y'): ?string
    {
        if (! $this->birthdate) {
            return null;
        }

        try {
            return $this->birthdate->format($format);
        } catch (\Exception $e) {
            return null;
        }
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
