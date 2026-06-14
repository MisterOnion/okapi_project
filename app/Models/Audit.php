<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    public $timestamps = false; // use change_at

    protected $fillable = [
        'lead_id',
        'event',
        'field',
        'old_value',
        'new_value',
        'changed_at',
    ];

    public function lead(){
        return $this->belongsTo(Lead::class);
    }
}
