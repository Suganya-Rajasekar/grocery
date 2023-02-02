<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\EmployeeBooking;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function Bookings(Request $request)
    {
        $user = auth('api')->user();
        if($user->role == 3)
        {
            $book = Book::select(/*$select*/)->where('emp_id',$user->id)->get()->map(function ($result) {
            $result->append('addon_list','category_name','service_name');
            return $result;
            });
            $book = $book->makeHidden(['category','service','addon','user_id','emp_id','period','card_no','created_at','updated_at','deleted_at']);
            $this->SuccessResponse($book,'Bookings Assigned to you');
        }else
        {
            $this->ErrorResponse('Insuffucient Permission');
        }
    }
}
