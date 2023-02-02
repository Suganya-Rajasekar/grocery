@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
 {{--    @if(isset($alloffer->promos))
    <div class="carousel">
        <div class="container-fluid">
            <div class="rest_grocery m-0" id="owlcarousel-rest">
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
    @include('home.tabs')
    <div class="container-fluid">   
       <!--  <section class="chefsfood-search"> -->
         <div class="searchbyfood">
            <div class="food-lists">
                @if(count($seemore) > 0 && $module == 'popular_near_you')
                @foreach($seemore as $k => $v)
                <div class="food-grid">
                    <div class="food-lists-img">
                        <a href="{!!url('/chef/'.$v->id)!!}" ><img src="{{$v->avatar}}" alt="" ></a>
                    </div>
                    <div class="food-list-det">
                        <a href="{!!url('/chef/'.$v->id)!!}" ><h2 class="font-weight-bold text-black">{{$v->name}}</h2></a>
                        <h3 class="text-muted elipsis-text my-3">{{ strip_tags($v->description) }}</h3>
                        <p class="text-muted">Customizable</p>
                        <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @include('home.seemore_page')
        <div class="paginate"></div>
    </div>
</div>
@endsection