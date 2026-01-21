<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Kategori extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable, HasUuids;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
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
        $kategori = self::findOrFail($id);
        $kategori->fill($data);
        $kategori->save();
        return $kategori;
    }

    public static function hapusData($id)
    {
        $kategori = self::findOrFail($id);
        $kategori->delete();
        return $kategori;
    }
}
