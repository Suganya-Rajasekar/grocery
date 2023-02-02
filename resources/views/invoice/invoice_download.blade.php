<!DOCTYPE html>
<html>
   <body>
      <div class="">
         <div class="">
            <div class="invoice-asw">
               <div class="row justify-content-between">
                  <div class="col-md-6 col-lg-6 ">
                     <img  src="{{ asset('assets/front/img/logo.svg') }}" alt="" style="width: 80px;">
                  </div>
                  <div class="col-lg-12 col-md-12 text-center">
                        <span class="">Tax Invoice</span>
                  </div>
                  <div class="col-lg-6 col-md-6 text-right">
                     <div class="invoice-details top-1">
                        <span class="">Emperica tech Solutions pvt ltd</span>
                        <ul class="p-0">
                           <li>Invoice No: <span class="">{!! $Invoice->number !!}</span></li>
                           <li>Invoice date: <span class="">{!! date_format($Invoice->created_at,"d-M-Y") !!}</span></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="top-2" >
                  <div class="row">
                     <div class="col-md-6 col-lg-6">
                        <span class="">Chef Details</span>
                        <ul class="p-0">
                           <li><span>Name: </span>{!! $payout->getVendorDetails->name !!}</li>
                           <li><span>Code: </span>{!! $payout->getVendorDetails->user_code !!}</li>
                           <li class="address"><span>Address: </span>{!! $payout->getVendorDetails->singlerestaurant->adrs_line_1 !!}</li>
                           <li>9626847411</li>
                           @if($payout->getVendorDetails->getDocument->gst_declaration ==1)
                           <li><span>GSTIN: </span>{!! $payout->getVendorDetails->getDocument->gst_no !!}</li>
                           @endif
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="top-3 mt-n5">
                  <div class="">
                     <h4 class="">Work Description</h4>
                  </div>
                  <div class="invoice table-responsive">
                     <table class="table">
                        <thead class="" style="background-color:#90A4AE;border-top:2px; color: white;">
                           <tr class="" >
                              <th>
                                    <b>Sr. No</b>
                              </th>
                              <th >
                                 <b >Particulars</b>
                              </th>
                              <th class="text-right" >
                                 <b>Amount (Rs.)</b>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td scope="col" class=""></td>
                              <td scope="col" class=" text-right"><b>Period:</b> {!! date('d-M-Y',strtotime($payout->from_date)) !!} To {!!  date('d-M-Y',strtotime($payout->to_date)) !!}</td>
                              <td scope="col" class=" text-right"></td>
                           </tr>
                           <tr>
                              <td scope="col" class="">1</td>
                              <td scope="col" class=" text-right">Commission for usage of Knosh's Online Ordering Platform</td>
                              <td scope="col" class=" text-right">{!! number_format($Commission,2) !!}</td>
                           </tr>
                           <tr>
                              <td scope="col" class=""></td>
                              <td scope="col" class=" text-right"><b>Total Amount:</b></td>
                              <td scope="col" class=" text-right">{!! number_format($Commission,2) !!}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /basic responsive configuration -->
      <style type="text/css">
         .top-1 span, .top-2 span, .top-3 h4{
         font-size: 20px;
         font-weight: bold;
         color: #373b5a;
         }
         .top-1 li span, .top-2 li span{
         font-size: 16px;
         font-weight: 400;
         }
         .top-2 li.address{
         width: 300px;
         }
         table tbody td {
         padding: 8px 20px !important;
         font-size: 14px;
         }
         .bg{
         background-color: #e0f7f4;
         }
         body {
         margin: 0px;
         }
         *{
         font-family: Verdana, Arial, sans-serif;
         }
         ul,li {
         text-decoration: none;
         list-style-type: none;
         margin: 0px;
         }
         table {
         font-size: 14px;
         border-collapse: collapse; 
         width: 100%;
         padding:10px;
         }
         .table tr td, .table thead th, .table th{
         font-size: 14px;
         border: 0px solid transparent;
         border: 0px solid transparent;
         }
         .table tr td{
         padding-left: 0px; 
         padding-right: 0px;
         }
         td{
         border:1px solid #ccc;
         }
      </style>
      <footer>
         <p style="text-align:center;"><BR>Emperica Tech Solutions Pvt ltd</p>
      </footer>
   </body>
</html>

