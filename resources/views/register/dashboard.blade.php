@extends('main.app')

@section('content')
<section class="">
    <div class="user_dashboard mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold f_30">Dashboard</h5>
                    <p class="f_20">Welcome back, Customer Demo!</p>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-md-6">
                     <div class="box-div">
                        <h5 class="float-left font-weight-bold darkblue f_20 my-4 px-3">Recently Subscribed Plan</h5>
                        <button class="btn float-right bg_blue view_more_btn f_18 mx-2 my-4 font-weight-bold">View more</button>
                        <div class="table-responsive">
                            <table id="datatable " class="table c_table " cellspacing="30px" >
                                <thead>
                            <tr>
                                <th>Company/Bussines/Website</th>
                                <th>Plane name</th>
                                <th>Amount</th>
                                <th>Date added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-div">
                        <h5 class="float-left darkblue font-weight-bold f_20 my-4 px-3">Recently Unsubscribed Plan</h5>
                        <button class="btn float-right bg_blue view_more_btn f_18 mx-2 my-4 font-weight-bold">View more</button>
                        <div class="table-responsive">
                            <table id="datatable " class="table c_table " cellspacing="30px" >
                                <thead>
                            <tr>
                                <th>Company/Bussines/Website</th>
                                <th>Plane name</th>
                                <th>Amount</th>
                                <th>Date unsubscribed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row my-5 recently_payment" >
                <div class="col-md-6">
                     <div class="box-div">
                        <h5 class="float-left darkblue font-weight-bold f_20 my-4 px-3">Recently Payment Done</h5>
                        <button class="btn float-right bg_blue view_more_btn f_18 mx-2 my-4 font-weight-bold">View more</button>
                        <div class="table-responsive">
                            <table id="datatable " class="table c_table " cellspacing="30px" >
                                <thead>
                            <tr>
                                <th>Company/Bussines/Website</th>
                                <th>Plane name</th>
                                <th>Amount</th>
                                <th>Date paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>Enterprise</td>
                                <td>$499.00</td>
                                <td>September 29, 2020</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-div">
                        <h5 class="float-left darkblue font-weight-bold f_20 my-4 px-3">Recently Plan Offers</h5>
                        <button class="btn float-right bg_blue view_more_btn f_18 mx-2 my-4 font-weight-bold">View more</button>
                        <div class="table-responsive">
                            <table id="datatable " class="table c_table " cellspacing="30px" >
                                <thead>
                            <tr>
                                <th>Company/Bussines/Website</th>
                                <th>Plane Offer</th>
                                <th>Valid until</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Adobe Creative Suite</td>
                                <td>10% offer for next purchase</td>
                                <td>September 29, 2020</td>
                            </tr>
                             <tr>
                                <td>Adobe Creative Suite</td>
                                <td>10% offer for next purchase</td>
                                <td>September 29, 2020</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

