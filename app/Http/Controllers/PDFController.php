<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
use App\Models\Order;
use App\Models\Orderdetail;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {

        $resultData = Order::where('id',$request->id)->with('Orderdetail')->first();
        $data = [
            'title' => 'Emperica - Invoice',
            'date' => date('m/d/Y'),
            'resultData'=>$resultData,
        ];
        
        if($resultData->order_type == "ticket") {
            $pdf = PDF::loadView('EventPdf',$data);
        } else {
            $pdf = PDF::loadView('myPDF', $data);
        } 
    
        return $pdf->download('Invoice#'.$request->id.'.pdf');
    }
    public function generateVendorPDF(Request $request)
    {

        $resultData = Orderdetail::where('id',$request->id)->first();
        $data = [
            'title' => 'Emperica - Invoice',
            'date' => date('m/d/Y'),
            'resultData'=>$resultData,
        ];
          
        $pdf = PDF::loadView('myPDFvendor', $data);
    
        return $pdf->download('Invoice#'.$request->id.'.pdf');
    }
    
}