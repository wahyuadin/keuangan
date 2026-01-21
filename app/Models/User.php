<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes, AuditingAuditable;

    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function showData($id = null)
    {
        return $id ? self::find($id)->first() : self::latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $user = self::findOrFail($id);
        $user->fill($data);
        $user->save();
        return $user;
    }

    public static function hapusData($id)
    {
        $user = self::findOrFail($id);
        $user->delete();
        return $user;
    }
}
