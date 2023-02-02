<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use App\Models\User;
use File;
use Flash;

class AuthController extends Controller
{
   /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        if($user->email != '') {
            $authUser = $this->findOrCreateUser($user, $provider);
            Flash::success($authUser->getData()->message);
            return redirect('user/dashboard/profile');
        } else {
            Flash::success('You cannot use social login');
            return redirect()->to('/');
        }
        
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        if($user->email == Null){
            $user->email = $user->id.'@gmail.com';
        }
        $authUser = User::where('email', $user->email)->first();
        /*$providerField = "{$provider}_id";
        if($authUser){
            if ($authUser->{$providerField} == $user->id) {
            $authUser->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
            $authUser->save();
            return $authUser;
            }
            else{
            $authUser->{$providerField} = $user->id;
            $authUser->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
            $authUser->save();
            return $authUser;
            }
        }*/
        if (!$authUser){
            if($user->avatar != NULL && $user->avatar != ""){
                $fileContents = file_get_contents($user->getAvatar());
                $user_profile = File::put(storage_path() . '/app/public/avatar/' . $user->getId() . ".jpg", $fileContents);
                $name = $user->getId() . ".jpg";
            }
            else {
                $name = NULL;
            }

            $role_id    = '2';
            $social_id  = $user->id;
            $provider   = $provider;
            $device     = 'web';
            $uname       = $user->name;
            $email      = $user->email;
            $avatar     = $name;
            $authUser = app()->call('App\Http\Controllers\Api\AuthController@socialLogin',[
                'request' => request()->merge(['social_id'=>$social_id,'provider'=>$provider,'role_id'=>$role_id,'device'=>$device,'email'=>$email,'name'=>$uname,'avatar'=>$avatar ])
            ]);

        } else {

            $role_id    = '2';
            $social_id  = $user->id;
            $provider   = $provider;
            $device     = 'web';
            $uname       = $authUser->name;
            $email      = $authUser->email;
            $authUser = app()->call('App\Http\Controllers\Api\AuthController@socialLogin',[
                'request' => request()->merge(['social_id'=>$social_id,'provider'=>$provider,'role_id'=>$role_id,'device'=>$device,'email'=>$email,'name'=>$uname])
            ]);

        }

        return $authUser;
        
    }
}
