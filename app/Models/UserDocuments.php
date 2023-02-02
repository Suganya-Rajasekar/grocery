<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use Illuminate\Support\Facades\Schema;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @author Suganya
 * @version Mar 17, 2021
 */
class UserDocuments extends Model
{
	use Log;
	
	public $table = 'user_documents';

	public $basicImage = '';

	protected $appends = [
		'on_boarding_form_src',
		'enrollment_form_src',
		'pan_card_src',
		'cancelled_cheque_src',
		'address_proof_src',
		'fssai_certificate_src',
		'aadar_image_src',
		'gst_certificate_src'
	];

	function __construct()
	{
		$this->basicImage = \URL::to('storage/app/public/');
	}

	public function getOnBoardingFormSrcAttribute()
	{
		return $this->sendFileName('on_boarding_form');
	}
	public function getEnrollmentFormSrcAttribute()
	{
		return $this->sendFileName('enrollment_form');
	}
	public function getPanCardSrcAttribute()
	{
		return $this->sendFileName('pan_card');
	}
	public function getCancelledChequeSrcAttribute()
	{
		return $this->sendFileName('cancelled_cheque');
	}
	public function getAddressProofSrcAttribute()
	{
		return $this->sendFileName('address_proof');
	}
	public function getFssaiCertificateSrcAttribute()
	{
		return $this->sendFileName('fssai_certificate');
	}
	public function getAadarImageSrcAttribute()
	{
		return $this->sendFileName('aadar_image');
	}
	public function getGstCertificateSrcAttribute()
	{
		return $this->sendFileName('gst_certificate');
	}

	public function sendFileName($attribute = '')
	{
		$url	= '';
		if ($attribute == 'on_boarding_form' || $attribute == 'enrollment_form' || $attribute == 'cancelled_cheque' || $attribute == 'gst_certificate')  {
			$url	= $this->basicImage.'/'.$attribute.'.pdf';
		}
		if ($this->attributes[$attribute] != '') {
			$fileName	= $this->attributes[$attribute];
			$path		= 'storage/user_document/'.$this->attributes['user_id'].'/'.$fileName;
			if(\File::exists(base_path($path))) {
				$url	= \URL::to($path);
			}
		}
		return $url;
	}


	public function getTableColumns()
	{
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
	public function scopePending($query)
    {
        $query->where('status','pending');
    }

}
