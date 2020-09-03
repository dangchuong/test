<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Models\TmpCsv;

class Csv extends Model
{
    protected $fillable = ['name', 'path', 'size'];

    public function tmpCsvs() {
    	return $this->hasMany(TmpCsv::class, 'csv_id', 'id');
    }
}
