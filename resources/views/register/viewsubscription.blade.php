@extends('main.app')

@section('content')
<section class="">
    <div class="userss user_vewsubscriptplans mt-5">
        <div class="container-fluid">
            <div class="row ">
                 <div class="col-md-12">
                <h5 class="font-weight-bold f_30"><span class=" fa fa-arrow-left  blue pr-4 mr-2"></span>Adobe</h5>
                <div class=""><span class="f_20 pr-3">Listing</span><span class="f_20 pr-2">Adobe</span></div>
            </div>
            </div>
            <div class="row">
              <div class="col-md-4  mt-5">
                <div class="box-div" style="overflow: hidden;"> 
                        <h5 class="  darkblue font-weight-bold  f_20 my-4 px-3">Plan details</h5>
                        <hr>
                   <ul>
                     <li><span class="float-left">Company Business name</span><span class="float-right">Adobe</span></li>
                     <li><span class="float-left">Wesite url</span><span class="float-right">Adobe.com</span></li>
                     <li><span class="float-left">Name of the plan</span><span class="float-right">Adobe creative suite</span></li>
                     <li><span class="float-left">Cost of the plane</span><span class="float-right">$499.99</span></li>
                     <li><span class="float-left">Expairy date of the plan</span><span class="float-right">October 29, 2020</span></li>
                     <li><button class="btn bg_blue view_more_btn font-weight-bold bluebtn_h my-4 py-3 "  data-toggle="modal" data-target="#exampleModal" style="border-radius: 35px; width:100%">Unsubscribe Plan</button></li>
                   </ul>
              </div>     
            </div>
              <div class="col-md-8  mt-5">
                 <div class="box-div" style="overflow:hidden">
                        <h5 class="  darkblue font-weight-bold  f_20 my-4 px-3">Payment Transaction</h5>
                        <div class="table-responsive">
                          <table id="datatable " class="table c_table mt-2" cellspacing="30px" >
                                <thead class="thead-light">
                            <tr>
                                <th>Date & time transaction</th>
                                <th>Amount paid</th>
                                <th>Payment method</th>
                                <th>Trasaction ID</th>
                            </tr>
                        </thead>
                        <tbody>
                               <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>$499.00</td>
                                <td>PayPal</td>
                                <td>REF 213214523</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
              </div>  
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="unsubscribe_modal">
            <div class="text-center">
              <p class=" f_12">LIMITED TIME OFFER</p>
              <div class="perc_img">
                <img src="">
              </div>
              <h5 class="font-weight-bold">10% Discount off</h5>
              <p class="f_12">Avail the 10% off now for your next billing</p>
              <div class="col-md-12 text-center ">
                  <button class="btn btn_b_radius graybtn_h px-5">Cancel</button>
                  <button class="btn btn_b_radius bluebtn_h bg_blue px-5">Submit</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
</div>
</section>

@endsection

