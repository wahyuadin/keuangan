<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Item extends Model implements Auditable
{
    use AuditingAuditable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function klinik()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_id', 'id');
    }

    public static function showData($id = null)
    {
        return $id ? self::find($id)->with('kategori', 'klinik', 'branch')->first() : self::with('kategori', 'klinik', 'branch')->latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $item = self::findOrFail($id);
        $item->fill($data);
        $item->save();

        return $item;
    }

    public static function hapusData($id)
    {
        $item = self::findOrFail($id);
        $item->delete();

        return $item;
    }
}
