<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,Redirect;
use App\Models\UserPayments;

class StripeController extends Controller
{
   public function getindex()
   {
      $data['AllPayments'] = UserPayments::find(\Auth::user()->id);
      return view('register.paymentgateway',$data);
   }
   public function test()
   {
         // return RazorRefund('pay_HzdG0SasVq1bD7',10);
         // return FCM('cFPkYLqqSl6dm79hUuZ4Qm:APA91bHCHtTJhCs5LnrmXBzaluFnwB3ZZZYDAiBhWukhlJII5xpOWE4QBcaqfMk94dSpaUViUE_d0iW5GtgET2MN5hS6ZuqTfg2gzl_DcfgOvVyi0Na99dSIRFG6BluPXDqwHedDWL5N','New Content!','A new video has been uploaded.');
   }

   public function Stripeconnect()
   {
      return Redirect::to('https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.CNF_STRIPE_CONNECT.'&scope=read_write&redirect_uri=http://localhost/subscripty/callback/stripe');
      exit();
   }
   public function Stripecallback(Request $request)
   {
        $Stripe_OAuth_token = $request->code;
        \Stripe\Stripe::setApiKey('sk_test_X90SMQiBFToZLMCtWjtMpKxx00G2Sf2eEs');

        $response = \Stripe\OAuth::token([
          'grant_type' => 'authorization_code',
          'code' => $Stripe_OAuth_token,
        ]);
        // Access the connected account id in the response
        $Stripe_account_id = $response->stripe_user_id;
        $user_id           = \Auth::user()->id;
        $UserPayments = UserPayments::findOrCreate($user_id);
        $UserPayments->stripe = $Stripe_account_id;
        $UserPayments->user_id= $user_id;
        $UserPayments = $UserPayments->save();
        return Redirect::to('paymentmode');
   }
   public function Stripeportal()
   {
      \Stripe\Stripe::setApiKey('sk_test_X90SMQiBFToZLMCtWjtMpKxx00G2Sf2eEs');
      $StripeCustomerId = UserPayments::find(\Auth::user()->id);
      print_r($StripeCustomerId->toArray());exit();
      // Authenticate your user.
      $session = \Stripe\BillingPortal\Session::create([
        'customer' => $StripeCustomerId,
        'return_url' => 'http://localhost/subscripty',
      ]);

      // Redirect to the customer portal.
      return Redirect::to($session->url); exit();
         }
}

// $stripe = new \Stripe\StripeClient(
//   'sk_test_X90SMQiBFToZLMCtWjtMpKxx00G2Sf2eEs'
// );
// $stripe->accounts->retrieve(
//   'acct_1FRcvhCFzS5FFpER',
//   []
// );

// $stripe = new \Stripe\StripeClient(
// 		  'sk_test_X90SMQiBFToZLMCtWjtMpKxx00G2Sf2eEs'
// 		);
// 		 $s = $stripe->subscriptions->all(['limit' => 3]);