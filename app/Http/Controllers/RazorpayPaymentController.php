<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;

class RazorpayPaymentController extends Controller
{
    private $Api;
    public function __construct()
    {
        $this->Api = new Api(\Config::get('razorpay')['RAZORPAY_KEY'], \Config::get('razorpay')['RAZORPAY_SECRET']);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {        
        return view('razorpayView');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $input  = $request->all();
        $api    = $this->Api;
        $payment= $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                if($response->status == 'captured'){
                    $type           = $request->type;
                    $orderinsert    = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['payment_type'=>'razorpay','payment_token'=>$response->id])]);
                    return \Redirect::to('thankyou');
                }
            } catch (Exception $e) {
                return  $e->getMessage();
                Session::put('error',$e->getMessage());
                return redirect()->back();
            }
        }
        return redirect()->back();
    }

    public function getNewOrderid($grand_total){
        $api    = $this->Api;
        $rorderidcreation   = $api->order->create([
            'amount'          => round($grand_total*100),
            'currency'        => 'INR',
            'payment_capture' =>  1
        ]);
        return $rorderidcreation->id;
    }

    public function refund($token, $amount = 0)
    {
        $api        = $this->Api;
        $status     = true;
        $refund     = '';
        $paymentId  = $token;
        if(!empty($paymentId)){
            try {
                if (!empty($amount) && $amount > 0) {
                    $paisa  = $amount * 100;
                    $refund = $api->refund->create(array('payment_id' => $paymentId, 'amount'=>$paisa));
                } else {
                    $refund = $api->refund->create(array('payment_id' => $paymentId));
                }
                $message = $refund->status;
            } 
            catch (Exception $e){
                $status  = false;
                $message = $e->getMessage();
            }

        }else{
            $status  = false;
            $message = "No Razorpay Payment ID";
        }
        $data['status'] = $status;
        $data['refund'] = $refund;
        $data['amount'] = $amount;
        $data['message']= $message;
        return $data;
    }
}