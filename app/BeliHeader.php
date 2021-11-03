<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeliHeader extends Model
{
    protected $table = 'beli_header';
	protected $primaryKey = 'ID_BELI_HEADER';
	public $timestamps = false;
    protected $guarded = [];
}
