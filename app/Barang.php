<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
	protected $primaryKey = 'ID_barang';
	public $timestamps = false;
    protected $guarded = [];
}
