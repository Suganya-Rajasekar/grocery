<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLog;
use Illuminate\Http\Request;

class DeliverylogController extends Controller
{
    public function index(Request $request)
    {
        $pageCount  = 10;
        $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $data       = new DeliveryLog;
        if($request->orderid){
            $data   = $data->where('order_id',$request->orderid);
        }
        $data   = $data->paginate(10);
        return view('deliverylog.index',compact('data','page'));
    }

}
