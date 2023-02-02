@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
  {{--   @if(isset($alloffer->promos))
    <div class="carousel">
        <div class="container-fluid">
            <div class="row pt-sm-50 rest_grocery m-0 owl-carousel owl-carousel-cui owl-loaded owl-drag" id="owlcarousel-rest">
                <div class="car-asw">
                    <div class="owl-carousel top-area-car owl-theme">
                        @foreach($alloffer->promos as $off_ke => $off_val)
                        <div class="item">
                            <div>
                                <a href="{!!url('/chefoffer/'.$off_val->id.'/'.\Request::segment(3).'/'.\Request::segment(4) )!!}" ><img src="{{$off_val->image}}" alt=""/></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif --}}
</section>

<div class="col-lg-12">
@include('home.tabs')
</div>

<div class="cuisine-bg1 py-5">
    <div class="area-asw">
        <div class="container-lg">
            <div class="searchbyfood">
                <div class="">
                    {{-- @if(count($cuisine) > 0) --}}
                    <div class="">
                        <div class="cuisine-backdrop d-none"> 
                            <div class="cusine-active-close">
                                <i class="fas fa-close"></i>
                            </div>
                        </div>
                        <div class="nav nav-tabs mb-5">
                            <div class="owl-cuisine row m-0">
                                <?php $i = 0; ?>
                                @foreach($cuisine as $ke => $val)
                                <div class="item col-md-6 p-0">
                                    <div class="nav-item text-center">
                                        <div class="cuisine d-md-flex d-none"  data-toggle="tab" href="#cuisinechef1" onclick="seecuisinechef('cuisines','{{$val->id}}')" id="{{-- cui{{$val->id}} --}}">
                                            <div class="cuisine-img w-100 @if(($i%4)>=2) d-none @endif " >
                                                <img src="{!! $val->image !!}" alt="">
                                            </div>
                                            <div class="w-100 content">
                                                <h2 class="elipsis-text font-montserrat">{!! $val->name !!}</h2>
                                                <p>{!! $val->description !!}</p>
                                                <p class="readmore">Read more</p>
                                            </div>
                                            <div class="cuisine-img w-100 @if(($i%4)<2) d-none @endif">
                                                <img src="{!! $val->image !!}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection