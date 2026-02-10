<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Sla extends Model implements Auditable
{
    use AuditingAuditable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function klinik()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public static function showData($id = null)
    {
        return $id ? self::find($id)->with('item.kategori', 'klinik.branch')->first() : self::latest()->with('item.kategori', 'klinik.branch')->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $report = self::findOrFail($id);
        $report->fill($data);
        $report->save();

        return $report;
    }

    public static function hapusData($id)
    {
        $report = self::findOrFail($id);
        $report->delete();

        return $report;
    }
}
