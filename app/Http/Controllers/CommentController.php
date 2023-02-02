<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Customer;
use App\Models\Comment;
use App\Models\Menuitems;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CommentExport;


class CommentController extends Controller
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

        $date    = $request->query('date') ? $request->query('date') : '';
        $search = $request->query('search');
        $pageCount=10;
        $page=(($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        \DB::enableQueryLog();
        $customerData   = Customer::orwhere('role',3)->get();
        $foodData   = Menuitems::get();
        $resultData = new Comment;
        if ($request->query('date') != '') {
            $sDate  = explode(" - ",$request->date);
            $resultData    = $resultData->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        } 
        if ($request->query('user_id') != '') {
            $resultData = $resultData->where('user_id',$request->query('user_id'));
        }
        if ($request->query('food_id') != '') {
            $resultData = $resultData->where('food_id',$request->query('food_id'));
        }
        if ($request->query('status') != '') {
            $resultData = $resultData->where('status',$request->query('status'));
        }
        if ($request->query('search') != ''){
            $resultData = $resultData->Where('comment', 'like', '%'.$search.'%');
        }
        $resultData = $resultData->paginate($pageCount);
        return view('comment.index',compact('resultData','page','search','customerData','foodData'));
        
    }

    public function edit(Request $request, $id)
    {
        $comment=Comment::find($id);
        return view('comment.form',compact('comment'));
    }
    
    public function update(Request $request){
        if($request->id>0){
            $comment=Comment::find($request->id);
        }else{
            $comment=new Comment;            
        }
            
        $comment->status=$request->status;
        $comment->reason=isset($request->reason) ? $request->reason : '';
        $comment->save();

        Flash::success('Comment status updated successfully.');
        return redirect(getRoleName().'/comment');
    }

    public function destroy($id)
    {
        $result =  Comment::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Comment detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/comment');

    }

    public function commentexport(Request $request,$slug)
    {
        $request->all();
        $exporter = app()->makeWith(CommentExport::class, compact('request'));  
        return $exporter->download('Commentsreport_'.date('Y-m-d').'.'.$slug);
    }

}
