<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Primary key tabel
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_role',
        'id_area',
        'nama_user',
        'email',
        'password',
        'status_validasi',
        'foto_profile',
        'no_telp',
        'alamat',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel roles
     */
    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'id_role',
            'id_role'
        );
    }

    public function area()
    {
        return $this->belongsTo(
            Area::class,
            'id_area',
            'id_area'
        );
    }

    public function transaksiMaterial()
    {
        return $this->hasMany(
            TransaksiMaterial::class,
            'id_user',
            'id_user'
        );
    }

    /**
     * Relasi ke tabel surat_jalan
     */
    public function suratJalan()
    {
        return $this->hasMany(SuratJalan::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke tabel notifikasi
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }
}