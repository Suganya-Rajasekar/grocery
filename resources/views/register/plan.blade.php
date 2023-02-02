@extends('main.app') @section('content')
<section class="">
    <div class="user_subscriptionplans mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 addplan">
                    <div class="float-left ">
                        <h5 class=" font-weight-bold f_30">Subscription plans</h5>
                        <p class="f_20">Listing</p>
                    </div>
                    <div class="float-right cal"> <a class="btn bg_blue pull-right btns " href="subscriptions/add"><span class="fa fa-plus mr-2"></span>Add new Subscription plan</a> </div>
                </div>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col-md-12">
                    <ul class="nav nav-tabs creditbtn mb-4 mt-3" id="myTab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active  f_18" id="ongoing-tab" data-toggle="tab" href="#ongoing" role="tab" aria-controls="home" aria-expanded="true">Ongoing({{ count($Subsc) }})</a> </li>
                        <li class="nav-item"> <a class="nav-link f_18" id="subscribd-tab" data-toggle="tab" href="#subscribed" role="tab" aria-controls="profile" aria-expanded="false">Subscribed(23)</a> </li>
                        <li class="nav-item"> <a class="nav-link f_18" id="deleted-tab" data-toggle="tab" href="#deleted" role="tab" aria-controls="profile" aria-expanded="false">Deleted({{ count($DeletedSubsc) }})</a> </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-div m-1">
                        <div class="tab-content" id="myTabContent">
                            <div role="tabpanel" class="tab-pane fade active show" id="ongoing" aria-labelledby="home-tab" aria-expanded="true">
                                <div class="table-responsive">
                                    <table id="datatable " class="table c_table " cellspacing="30px">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Company Bussines Name</th>
                                                <th>Website Name</th>
                                                <th>Plan Name</th>
                                                <th>Price</th>
                                                <th>Last paid date</th>
                                                <th>Plan expiry date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Subsc as $sub)
                                            <tr>
                                                <td>{{ $sub->company_name }}</td>
                                                <td>{{ $sub->website }}</td>
                                                <td>{{ $sub->plan_name }}</td>
                                                <td>{{ getCurrencySymbol($sub->currency) }}{{ $sub->price }}</td>
                                                <td>{{ date_format_show($sub->last_paid_date) }}</td>
                                                <td>{{ date_format_show($sub->plan_expiry_date) }}</td>
                                                <td><a href="subscriptions/edit/{{ $sub->id }}"><span class="fa fa-search blue"></span></a><a onClick='deleteSubsc("{{ $sub->id }}")' class="cursor_pointer"><span class="fa fa-close text-danger"></span></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="subscribed" role="tabpanel" aria-labelledby="profile-tab" aria-expanded="false">
                                <div class="table-responsive">
                                    <table id="datatable " class="table c_table " cellspacing="30px">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Company Bussines Name</th>
                                                <th>Website Name</th>
                                                <th>Plan Name</th>
                                                <th>Price</th>
                                                <th>Last paid date</th>
                                                <th>Plan expiry date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Adobe</td>
                                                <td>Adobe.com</td>
                                                <td>Adobe Creative Suite</td>
                                                <td>$499.00</td>
                                                <td>September 29, 2020</td>
                                                <td>September 29, 2020</td>
                                                <td><span class="fa fa-search blue"></span><span class="fa fa-close text-danger"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Adobe</td>
                                                <td>Adobe.com</td>
                                                <td>Adobe Creative Suite</td>
                                                <td>$499.00</td>
                                                <td>September 29, 2020</td>
                                                <td>September 29, 2020</td>
                                                <td><span class="fa fa-search blue"></span><span class="fa fa-close text-danger"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Adobe</td>
                                                <td>Adobe.com</td>
                                                <td>Adobe Creative Suite</td>
                                                <td>$499.00</td>
                                                <td>September 29, 2020</td>
                                                <td>September 29, 2020</td>
                                                <td><span class="fa fa-search blue"></span><span class="fa fa-close text-danger"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="deleted" aria-labelledby="home-tab" aria-expanded="true">
                                <div class="table-responsive">
                                    <table id="datatable " class="table c_table " cellspacing="30px">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Company Bussines Name</th>
                                                <th>Website Name</th>
                                                <th>Plan Name</th>
                                                <th>Price</th>
                                                <th>Last paid date</th>
                                                <th>Plan expiry date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DeletedSubsc as $sub)
                                            <tr>
                                                <td>{{ $sub->company_name }}</td>
                                                <td>{{ $sub->website }}</td>
                                                <td>{{ $sub->plan_name }}</td>
                                                <td>{{ getCurrencySymbol($sub->currency) }}{{ $sub->price }}</td>
                                                <td>{{ date_format_show($sub->last_paid_date) }}</td>
                                                <td>{{ date_format_show($sub->plan_expiry_date) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection