<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Waktu extends Model {
	protected $table = 'waktu';
	protected $hidden = ['created_at', 'updated_at'];
	
	public function user_modify()
	{
		return $this->belongsTo('App\Model\User', 'user_modified');
	}
	
}