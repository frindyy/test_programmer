<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JualHeader extends Model
{
    protected $table = 'jual_header';
	protected $primaryKey = 'ID_JUAL_HEADER';
	public $timestamps = false;
    protected $guarded = [];
}
