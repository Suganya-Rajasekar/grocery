<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Customer;
use App\Models\Chefs;
use App\Models\Chefsreq;
use App\Models\Cuisines;
use App\Models\UserDocuments;
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use File;

class UserdocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $v_id           = $request->v_id;
        $userdocument   = UserDocuments::where('user_id',$v_id)->first();
        return view('userdocument.form',compact('userdocument'),compact('v_id'));
    }

    public function update(Request $request)
    {
        $userdocument   =  app()->call('App\Http\Controllers\Api\Partner\PartnerController@documents',$request->all());
        $data = $userdocument->getData();
        if ($userdocument->status() == 422) {
            \Flash::error($data->message);
            $request->flash();
            return \Redirect::back()->withErrors([])->withInput();
        }
        Flash::success($data->message);
        // return \Redirect::to(getRoleName().'/chef/'.$request->current_user_id.'/user_documents');
        return redirect()->back();
    }
}