<form class="form-inline " method="GET">
    {{-- @if(\Request::segment(2)=='earning_report' && \Request::segment(3)=='item') --}}

    @if(\Auth::user()->role==1 && \Request::segment(2)=='earning_report' )
    <div class="form-group mb-10">
        @if(\Request::segment(3)=='mis' )
        <?php 
        $chef_id=\Request::query('chef_id');
        if($chef_id) {
        $chef_id=array_filter($chef_id);
        if(empty($chef_id)){
        $chef_id='';
        } else{
        $chef_id=$chef_id;
        }
        }?>
        <select name="chef_id[]" class="select-search" multiple>
            <!--\Request::query('chef_id')  -->
            <option value="" {!! ($chef_id=='') ? 'selected' : '' !!}>All chefs</option>
            @if(count($chefs)>0)
            @foreach($chefs as $key=>$value)
            <option value="{!!$value->id!!}" @if(\Request::query('chef_id')!='' && in_array($value->id,\Request::query('chef_id')))  selected @endif>{!!$value->name!!}</option>
            @endforeach
            @endif
        </select>
        @else
        <select name="chef_id" class="select-search">
            <!--\Request::query('chef_id')  -->
            <option value="">All chefs</option>
            @if(count($chefs)>0)
            @foreach($chefs as $key=>$value)
            <option value="{!!$value->id!!}" {!! (\Request::query('chef_id')!='' && (\Request::query('chef_id')==$value->id)) ? 'selected' : '' !!}>{!!$value->name!!}</option>
            @endforeach
            @endif
        </select>
        @endif
    </div>
    <div class="form-group mb-10">
        @if(\Request::segment(3)=='mis' )
        <?php 
        $location_id=\Request::query('location_id');
        if($location_id) {
        $location_id=array_filter($location_id);
        if(empty($location_id)){
        $location_id='';
        } else{
        $location_id=$location_id;
        }
        }?>
        <select name="location_id[]" class="select-search" multiple>
            <option value="" {!! ($location_id=='') ? 'selected' : '' !!}>All locations</option>
            @if(count($location)>0)
            @foreach($location as $l_key=>$l_value)
            <option value="{!!$l_value->id!!}" @if(\Request::query('location_id')!='' && in_array($l_value->id,\Request::query('location_id')))  selected @endif>{!!$l_value->name!!}</option>
            @endforeach
            @endif
        </select>
        @else
        <select name="location_id" class="select-search">
            <!--\Request::query('chef_id')  -->
            <option value="">All locations</option>
            @if(count($location)>0)
            @foreach($location as $l_key=>$l_value)
            <option value="{!!$l_value->id!!}" {!! (\Request::query('location_id')!='' && (\Request::query('location_id')==$l_value->id)) ? 'selected' : '' !!}>{!!$l_value->name!!}</option>
            @endforeach
            @endif
        </select>
        @endif
    </div>
    @endif
    {{-- @endif --}}
    @if(\Request::segment(2)=='earning_report')
	<div class="form-group mb-2">
       <input type="text" class="form-control daterange-basic" id="date" name="date" value="{!! (isset($date) && $date!='') ? $date : '' !!}">
    </div>
    @endif
	@if(\Request::segment(2)=='payout')
    <div class="form-group mb-2">
       <input type="text" class="form-control monthonly" id="month" name="month" data-date="102/2012" data-date-format="mm-yyyy" data-date-viewmode="years" data-date-minviewmode="months" value="{!! $monthpick ?? '' !!}">
    </div>
    @endif
    <div class="form-group mb-2">
        <input type="text" class="form-control" id="filter" name="filter" placeholder="Search..." value="{{$filter}}">
    </div>
    <div class="form-group mb-2">
        <input type="hidden" class="form-control" id="type" name="type" value="{{$type}}">
    </div>
    <button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
    @if(\Request::segment(2)=='earning_report')
	<button type="button" class="btn btn-primary ml-2 mb-2 downloadfile"><i class="fa fa-download"></i></button>
    @endif
</form> 