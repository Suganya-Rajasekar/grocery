<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Payout;
use App\Models\Chefs;
use App\Models\Tds;
use File;
use Illuminate\Support\Facades\Crypt;


class TdsController extends Controller
{

	public function index()
	{
		$tds      = Tds::paginate(10);
	}
	public function create()
	{
		$tds      = Tds::get();
		$chef 	  = Chefs::select('*')->where('role','3')->get();
		return view('payout.form',compact('tds','chef'));
	}
	public function store(Request $request)
	{
		$tds = new Tds();
		$tds->chef  	 = $request->chef_id;
		$tds->fq_quarter = $request->fq_quarter;
		$tds->start_date = $request->start_date;
		$tds->end_date   = $request->end_date;
		if(isset($request->certificate) && $request->hasFile('certificate')) {
			$mainPathString	= 'tds_document/';
			$mainPath		= storage_path($mainPathString);
			if (!File::exists($mainPath)) {
			    $dc = File::makeDirectory($mainPath, 0777, true, true);
			}
			$file			= $request->file('certificate');
			$newfilename	= $tds->chef.'-'.str_replace('/', '-', $tds->start_date).'.pdf';
			$uploadSuccess	= $file->move($mainPath, $newfilename);
		}
		$tds->certificate = $newfilename;
		$tds->save();
        Flash::success('TDS Uploaded successfully.');
		return redirect(getRoleName().'/payout/payout_tds');
	}
}
