<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Report extends Model implements Auditable
{
    use AuditingAuditable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function sla()
    {
        return $this->belongsTo(Sla::class, 'sla_id', 'id');
    }

    // public function branch()
    // {
    //     return $this->belongsTo(Branchoffice::class, 'branch_id', 'id');
    // }

    public static function showData($id = null)
    {
        return $id ? self::with('user', 'clinic.branch', 'item', 'sla')->whereHas('clinic.branch', function ($q) use ($id) {
            $q->where('id', $id);
        })->get() : self::latest()->with('user', 'clinic.branch', 'item', 'sla')->get();
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
