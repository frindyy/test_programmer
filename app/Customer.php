<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
	protected $primaryKey = 'ID_CUSTOMER';
	public $timestamps = false;
    protected $guarded = [];
}
