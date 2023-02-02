<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chefs;

class Reels extends Model
{
	use HasFactory;

	protected $table	= 'reels';
	protected $fillable	= ['title','description','video_magekit_thumburl','video_magekit_resizeurl','video_url','is_selected_chef','selected_chef_id','validity_date_time','status'];

	protected $casts = [
		'validity_date_time' => 'datetime'
	];

	protected $hidden = [
		'created_at', 'updated_at'
	];

	public function scopeActive($query)
	{
		$query->where('status','active');
	}
	
	public function chef()
	{
		return $this->hasOne(Chefs::class, 'id', 'selected_chef_id')->select('id','avatar','name');
	}
}