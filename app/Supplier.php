<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
	protected $primaryKey = 'ID_SUPPLIER';
	public $timestamps = false;
    protected $guarded = [];
}
