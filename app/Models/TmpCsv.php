<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Models\Csv;

class TmpCsv extends Model
{
    protected $table = 'tmp_csvs';

    protected $fillable = ['path', 'size'];

    public function csv() {
    	return $this->belongsTo(Csv::class, 'csv_id', 'id');
    }

    public final function save(array $Options = []) {
		parent::save($Options);

		/**
		 * It's very useful feature to return the object
		 * instance after saving instead a boolean value.
		 */
		return $this;
	}
}
