<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Module;
use App\Models\UserModule;
use Validator, Input, Redirect ;
use Illuminate\Http\Request;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	function __construct()
	{
		$this->method		= \Request::method();
		$this->segment		= \Request::segment(1);
		$this->paginate		= 10;
		$this->sub_paginate	= 10;
		$from	= (!is_null($this->segment) && $this->segment == 'api') ? 'mobile' : 'web';
		if ($from == 'mobile') {
			$cookie	= \Request::get('cookie');
			if($cookie) {	
				\Session::put('cookie',$cookie);
			}
		} else {
			$cookie	= ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
		}
		\Request::merge(['from'	=> $from,'cookie' => $cookie]);
	}

	function cookie()
	{
		$cookie = uniqid();
		\Cookie::queue(\Cookie::make("mycart", $cookie, 45000));
		\Session::put('cookie', $cookie);
		return $cookie;
	}

	function validateDatas($data,$rules,$custom_message=[],$niceNames = [],$from = 'api')
	{
		$validator	= Validator::make($data,$rules);
		if(count($niceNames) > 0){
			$validator->setAttributeNames($niceNames); 
		}
		if($validator->passes()) {
			if ($from == 'api') {
				return true;
			} else {
				return [];
			}
		} else {
			$response['message']	= getValidationErrorMsg($validator);
			if ($from == 'api') {
				$method = \Route::current()->getActionMethod();
				if ($method == 'MasterCron') {
					return \Response::json($response);
				} else {
					response()->json($response, 422)->send();exit();
				}
			} else {
				$response['validator']	= $validator;
				return $response;
			}
		}
	}

	function sendemail($view,$data,$from,$to,$subject)
	{
		try {
			\Mail::send($view,$data,function($message) use ($from,$to,$subject) {
				$message->to($to)->subject($subject);
				$message->from($from);
			});
		} catch (Exception $e) {
			
		}
		return;
	}

	function authCheck()
	{
		$userId = 0; $user = (object) [];$userCode = 0;
		$guard	= ($this->segment == 'api') ? 'api' : 'web';
		$user	= auth($guard)->user();
		if (!empty($user)) {
			$userId = $user->id;
			$userCode = $user->user_code;
		}
		$return['userId']	= $userId;
		$return['user']		= $user;
		$return['user_code']	= $userCode;
		return $return;
	}

	/*function validateDatas($data,$rules,$custom_message=[],$niceNames = [])
	{
		$validator = Validator::make($data,$rules);
		if(count($niceNames) > 0){
			$validator->setAttributeNames($niceNames); 
		}
		if($validator->passes()){
			return true;
		} else {
			$message = $this->getValidationErrorMsg($validator);
			$this->ErrorResponse($message);
		}
	}

	static function getValidationErrorMsg($validator)
	{
		$messages 	= $validator->messages();
		$error 		= $messages->getMessages();
		$val 		= 'Error';
		if(count($error) > 0){
			foreach ($error as $key => $value) {
				$val= $value[0];
				break;
			}
		}
		return $val;
	}*/

	public function SuccessResponse($data,$message)
	{
		$response['result'] = true;
		$response['data'] = $data;
		$response['message'] = $message;
		response()->json($response, 200)->send();exit();
	}

	public function ErrorResponse($message,$code = 422)
	{
		// $response['result'] = false;
		$response['message'] = $message;
		response()->json($response, $code)->send();exit();
	}
}
