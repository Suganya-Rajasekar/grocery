@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default partner-insites-asw">
    <div class="page-header-content">
        <div class="page-title">
            <h5>
                <span class="text-semibold">
                    Insights
                </span>
                - {!! \Auth::user()->name !!}
            </h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="{!! url('admin/dashboard') !!}">
                    <i class="icon-home2 position-left">
                    </i>
                    Dashboard
                </a>
            </li>
            <li>
                {{-- <a href="javascript::void();"> --}}
                    Insights
                {{-- </a> --}}
            </li>
        </ul>
    </div>
</div>
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content partner-insites-asw">
    <div class="">
        <div class="bg1-asw">
            <nav class="d-sm-flex justify-content-between py-4">
                <div>
                    <p class="subtitle">
                        Total sales from delivered order
                    </p>
                </div>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a aria-controls="nav-home" aria-selected="false" class="nav-link active" data-toggle="tab" href="#tab-1" id="nav-home-tab" role="tab">
                        This Month
                    </a>
                    <a aria-controls="nav-profile" aria-selected="true" class="nav-link" data-toggle="tab" href="#tab-2" id="nav-profile-tab" role="tab">
                        Previous
                    </a>
                    {{-- <a aria-controls="nav-custom" aria-selected="true" class="nav-link custom-asw" data-toggle="tab" href="#tab-3" id="nav-profile-tab" role="tab">custom</a>
                    <input type="text" name="daterange" id="datepicker" class="mt-3 form-control datepicker-input" placeholder="Select Date"  value="01/01/2021 - 01/15/2021" /> --}}
                </div>
            </nav>
            <div class="tab-content py-4 tab1-asw" id="nav-tabContent">
                <div aria-labelledby="nav-home-tab" class="tab-pane fade show active" id="tab-1" role="tabpanel">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['today'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['todaySum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['todayCnt'] !!} @if($insights['sales']['thisMonth']['todayCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thisweek'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['thsWekSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thsWekCnt'] !!} @if($insights['sales']['thisMonth']['thsWekCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thisMonth'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['thsMntSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thsMntCnt'] !!} @if($insights['sales']['thisMonth']['thsMntCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div aria-labelledby="nav-profile-tab" class="tab-pane fade" id="tab-2" role="tabpanel">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['yesterday'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['yesterSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['yesterCnt'] !!} @if($insights['sales']['previous']['yesterCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['lastWeek'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['latWekSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['latWekCnt'] !!} @if($insights['sales']['previous']['latWekCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['lastMoth'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['latMntSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['latMntCnt'] !!} @if($insights['sales']['previous']['latMntCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="bg1-asw mt-3 bg1-red">
            <nav class="d-sm-flex justify-content-between py-4">
                <div>
                    <p class="subtitle">
                        Total sales from delivered order
                    </p>
                </div>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a aria-controls="nav-home" aria-selected="false" class="nav-link active" data-toggle="tab" href="#tab-11" id="nav-home-tab" role="tab">
                        This Month
                    </a>
                    <a aria-controls="nav-profile" aria-selected="true" class="nav-link" data-toggle="tab" href="#tab-22" id="nav-profile-tab" role="tab">
                        Previous
                    </a>
                    <a aria-controls="nav-custom" aria-selected="true" class="nav-link custom-asw" data-toggle="tab" href="#tab-33" id="nav-profile-tab" role="tab">custom</a>
                    <input type="text" name="daterange" id="datepicker" class="mt-3 form-control datepicker-input" placeholder="Select Date"  value="01/01/2021 - 01/15/2021" />
                </div>
            </nav>
            <div class="tab-content py-4 tab1-asw" id="nav-tabContent">
                <div aria-labelledby="nav-home-tab" class="tab-pane fade show active" id="tab-11" role="tabpanel">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['today'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['todaySum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['todayCnt'] !!} @if($insights['sales']['thisMonth']['todayCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thisweek'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['thsWekSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thsWekCnt'] !!} @if($insights['sales']['thisMonth']['thsWekCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thisMonth'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['thisMonth']['thsMntSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['thisMonth']['thsMntCnt'] !!} @if($insights['sales']['thisMonth']['thsMntCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div aria-labelledby="nav-profile-tab" class="tab-pane fade" id="tab-22" role="tabpanel">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['yesterday'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['yesterSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['yesterCnt'] !!} @if($insights['sales']['previous']['yesterCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['lastWeek'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['latWekSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['latWekCnt'] !!} @if($insights['sales']['previous']['latWekCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-4 tab-col">
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['lastMoth'] !!}
                                </p>
                                <h3 class="mb-2">
                                    ₹ {!! $insights['sales']['previous']['latMntSum'] !!}
                                </h3>
                                <p class="mb-2">
                                    {!! $insights['sales']['previous']['latMntCnt'] !!} @if($insights['sales']['previous']['latMntCnt'] > 1){!! 'Orders' !!}@else{!! 'Order' !!}@endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="container-fluid bg2-asw">
            <div class="row">
                <div class="col-xl-6">
                    <div class="py-4 box-2 h-100">
                        <div class="title">
                            <h1>
                                Business report
                            </h1>
                        </div>
                        <div>
                            <nav class="py-3">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a aria-controls="nav-profile" aria-selected="true" class="nav-link active" data-toggle="tab" href="#tab2-2" id="nav-profile-tab" role="tab">
                                        Top Insights
                                    </a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div aria-labelledby="nav-profile-tab" class="tab-pane fadeshow active final-tab" id="tab2-2" role="tabpanel">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a aria-controls="nav-home" aria-selected="true" class="nav-link active" data-toggle="tab" href="#tab22-1" id="nav-home-tab" role="tab">
                                                Today
                                            </a>
                                            <a aria-controls="nav-profile" aria-selected="false" class="nav-link" data-toggle="tab" href="#tab22-2" id="nav-profile-tab" role="tab">
                                                This week
                                            </a>
                                            <a aria-controls="nav-contact" aria-selected="false" class="nav-link" data-toggle="tab" href="#tab22-3" id="nav-contact-tab" role="tab">
                                                This month
                                            </a>
                                            {{-- <a aria-controls="nav-profile" aria-selected="true" class="nav-link custom-asw" data-toggle="tab" href="#tab-2" id="nav-profile-tab" role="tab">custom</a>
                                            <input type="text" name="daterange" id="datepicker" class="mt-3 form-control datepicker-input" placeholder="Select Date"  value="01/01/2021 - 01/15/2021" /> --}}
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div aria-labelledby="nav-home-tab" class="tab-pane fade show active" id="tab22-1" role="tabpanel">
                                            <div>
                                                <div class="title">
                                                    <h4>{!! explode('-', $insights['insights']['today']->dates)[0] !!}</h4>
                                                    <h7>{!! $insights['insights']['today']->insights->completed !!} / {!! $insights['insights']['today']->total !!} (Completed / Total)</h7>
                                                </div>
                                                <div class="table-responsive-xl">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td>Pending Orders</td>
                                                                <td>{!! $insights['insights']['today']->insights->pending !!}</td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>Accepted Orders</td>
                                                                <td>{!! $insights['insights']['today']->insights->accepted_res !!}</td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>Delivered Orders</td>
                                                                <td>{!! $insights['insights']['today']->insights->completed !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cancelled Orders</td>
                                                                <td>{!! $insights['insights']['today']->insights->rejected_res !!}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div aria-labelledby="nav-profile-tab" class="tab-pane fade" id="tab22-2" role="tabpanel">
                                            <div>
                                                <div class="title">
                                                    <h4>{!! $insights['insights']['week']->dates !!}</h4>
                                                    <h7>{!! $insights['insights']['week']->insights->completed !!} / {!! $insights['insights']['week']->total !!} (Completed / Total)</h7>
                                                </div>
                                                <div class="table-responsive-xl">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td>Pending Orders</td>
                                                                <td>{!! $insights['insights']['week']->insights->pending !!}</td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>Accepted Orders</td>
                                                                <td>{!! $insights['insights']['week']->insights->accepted_res !!}</td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>Delivered Orders</td>
                                                                <td>{!! $insights['insights']['week']->insights->completed !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cancelled Orders</td>
                                                                <td>{!! $insights['insights']['week']->insights->rejected_res !!}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div aria-labelledby="nav-contact-tab" class="tab-pane fade" id="tab22-3" role="tabpanel">
                                            <div>
                                                <div class="title">
                                                    <h4>{!! $insights['insights']['month']->dates !!}</h4>
                                                    <h7>{!! $insights['insights']['month']->insights->completed !!} / {!! $insights['insights']['month']->total !!} (Completed / Total)</h7>
                                                </div>
                                                <div class="table-responsive-xl">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td>Pending Orders</td>
                                                                <td>{!! $insights['insights']['month']->insights->pending !!}</td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>Accepted Orders</td>
                                                                <td>{!! $insights['insights']['month']->insights->accepted_res !!}</td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>Delivered Orders</td>
                                                                <td>{!! $insights['insights']['month']->insights->completed !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cancelled Orders</td>
                                                                <td>{!! $insights['insights']['month']->insights->rejected_res !!}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="see-past ">
                                            <a href="">See Past orders</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mt-xl-0 mt-3">
                    <div class="py-4 box-3 box-2 h-100">
                        <div class="title">
                            <h1>Operational Metrics</h1>
                        </div>
                        <div>
                            <nav class="py-3">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a aria-controls="nav-profile" aria-selected="true" class="nav-link active" data-toggle="tab" href="#tab2-2" id="nav-profile-tab" role="tab">
                                        Total sales
                                    </a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div aria-labelledby="nav-profile-tab" class="tab-pane fadeshow active final-tab" id="tab2-2" role="tabpanel">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a aria-controls="nav-home" aria-selected="true" class="nav-link active" data-toggle="tab" href="#metrics-1" id="nav-today" role="tab">
                                                Today
                                            </a>
                                            <a aria-controls="nav-profile" aria-selected="false" class="nav-link" data-toggle="tab" href="#metrics-2" id="nav-week" role="tab">
                                                This week
                                            </a>
                                            <a aria-controls="nav-contact" aria-selected="false" class="nav-link" data-toggle="tab" href="#metrics-3" id="nav-month" role="tab">
                                                This month
                                            </a>
                                            {{-- <a aria-controls="nav-custom" aria-selected="true" class="nav-link custom-asw" data-toggle="tab" href="#tab-33" id="nav-profile-tab" role="tab">custom</a>
                                            <input type="text" name="daterange" id="datepicker" class="mt-3 form-control datepicker-input" placeholder="Select Date"  value="01/01/2021 - 01/15/2021" /> --}}
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div aria-labelledby="nav-today" class="tab-pane fade show active" id="metrics-1" role="tabpanel">
                                            <div class="container-fluid">
                                                <div class="row mt-sm-5">
                                                   {{--  <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Accepted Orders</h2>
                                                            <p>{!! $insights['insights']['today']->metrics->accepted !!}%</p>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Delivered Orders</h2>
                                                            <p>{!! $insights['insights']['today']->metrics->completed !!}%</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Cancelled Orders</h2>
                                                            <p>{!! $insights['insights']['today']->metrics->rejected !!}%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div aria-labelledby="nav-week" class="tab-pane fade" id="metrics-2" role="tabpanel">
                                            <div class="container-fluid">
                                                <div class="row mt-sm-5">
                                                   {{--  <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Accepted Orders</h2>
                                                            <p>{!! $insights['insights']['week']->metrics->accepted !!}%</p>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Delivered Orders</h2>
                                                            <p>{!! $insights['insights']['week']->metrics->completed !!}%</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Cancelled Orders</h2>
                                                            <p>{!! $insights['insights']['week']->metrics->rejected !!}%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div aria-labelledby="nav-month" class="tab-pane fade" id="metrics-3" role="tabpanel">
                                            <div class="container-fluid">
                                                <div class="row mt-sm-5">
                                                    {{-- <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Accepted Orders</h2>
                                                            <p>{!! $insights['insights']['month']->metrics->accepted !!}%</p>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Completed Orders</h2>
                                                            <p>{!! $insights['insights']['month']->metrics->completed !!}%</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="bg">
                                                            <h2>Cancelled Orders</h2>
                                                            <p>{!! $insights['insights']['month']->metrics->rejected !!}%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid bg2-asw revenue">
            <div class="title">
                <h3>
                    Revenue Data
                </h3>
                <form class="form-inline mt-4" method="GET">
                    <div class="form-group mb-2">
                        <label>From:</label>
                        <input type="date" class="form-control" name="from_date" id="date" value="">
                    </div>
                    <div class="form-group mb-2">
                        <label>To:</label>
                        <input type="date" class="form-control" name="to_date" id="date" value="">
                    </div>
                    <button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
                </form>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 rev_box">
                            <div class="bg d-flex">
                                <p class="alpha">A</p>
                                <div>
                                    <h2>Gross revenue</h2>
                                    <p><b>Rs.{{ $revenue->gross_revenue->amount }}</b></p>
                                    <p class="text-muted">Number of orders: {{ $revenue->gross_revenue->order_count }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 rev_box">
                            <div class="bg d-flex">
                                <p class="alpha">B</p>
                                <div><h2>Additions</h2>
                                    <p><b>Rs.{{ $revenue->additions->amount }}</b></p></div>
                                </div>
                            </div>

                            <div class="col-md-3 rev_box">
                                <div class="bg d-flex">
                                    <p class="alpha">C</p>
                                    <div><h2>Deductions</h2>
                                        <p><b>Rs.{{ $revenue->deductions->amount }}</b></p>   
                                    </div>                         
                                </div>
                            </div>

                            <div class="col-md-3 rev_box">
                                <div class="bg d-flex">
                                    <p class="alpha">D</p>
                                    <div><h2>Net receivable [A+B-C]</h2>
                                        <p><b>Rs.{{ $revenue->net_recievable->amount }}</b></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 rev_box">
                                <div class="bg d-flex">
                                    <p class="alpha">F</p>
                                    <div><h2>Amount transferable by Knosh</h2>
                                        <p><b>Rs.{{ $revenue->transferable->amount }}</b></p>

                                    </div>                           
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
