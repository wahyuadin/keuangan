<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class rkap extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable, HasUuids;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public static function showData($id = null)
    {
        return $id ? self::find($id)->with('item.kategori')->first() : self::latest()->with('item.kategori')->get();
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
