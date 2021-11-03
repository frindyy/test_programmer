<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JualDetail extends Model
{
    protected $table = 'jual_detail';
	protected $primaryKey = 'ID_JUAL_DETAIL';
	public $timestamps = false;
    protected $guarded = [];
}
