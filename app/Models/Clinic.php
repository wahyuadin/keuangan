<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Clinic extends Model implements Auditable
{
    use AuditingAuditable, HasFactory, HasUuids, SoftDeletes, SoftDeletes;

    protected $guarded = [];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function branch()
    {
        return $this->belongsTo(Branchoffice::class, 'branch_id');
    }

    public static function showData($id = null)
    {
        return $id ? self::find($id)->with('branch')->first() : self::with('branch')->latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $branch = self::findOrFail($id);
        $branch->fill($data);
        $branch->save();

        return $branch;
    }

    public static function hapusData($id)
    {
        $branch = self::findOrFail($id);
        $branch->delete();

        return $branch;
    }
}
