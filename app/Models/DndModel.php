<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DndModel extends Model
{
	public $timestamps = true;
	protected $table='tbl_dnd';
    protected $fillable = [
        'msisdn', 'dnd_status',
    ];
}
