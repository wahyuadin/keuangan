<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class BugReport extends Model implements Auditable
{
    use HasFactory, HasUuids, SoftDeletes, AuditingAuditable;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];


    public static function showData($id = null)
    {
        return $id ? self::where('user_id', $id)->get() : self::latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $agent = self::findOrFail($id);
        $agent->fill($data);
        $agent->save();
        return $agent;
    }

    public static function hapusData($id)
    {
        $agent = self::findOrFail($id);
        $agent->delete();
        return $agent;
    }
}
