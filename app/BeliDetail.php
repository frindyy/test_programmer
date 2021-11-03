<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeliDetail extends Model
{
    protected $table = 'beli_detail';
	protected $primaryKey = 'ID_BELI_DETAIL';
	public $timestamps = false;
    protected $guarded = [];
}
