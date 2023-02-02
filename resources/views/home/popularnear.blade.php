@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
 {{--  @if(isset($alloffer->promos))
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
@include('home.tabs')

  
  <div class="container-fluid area-asw ">
    <section>
      <div class="container-fluid">
        <div class="searchbyfood">
          <div class="row text-center">
            @if(count($seemore) > 0)
            @foreach($seemore as $k => $v)
            <div class="col-md-6 mt-4">
              <div class="food-list-det">
                <div class="d-flex w-100 df-1">
                  <div class="demo00-asw">
                    <div class="food-lists-img">
                      <a href="{!!url('/menuaddon/'.$v->id)!!}">
                        <img src="{{$v->image}}" alt="">
                      </a>
                    </div>
                  </div> 
                  <div class="text-left w-100 pl-1 pl-lg-3 demo000-asw">
                    <div class="d-flex w-100 df-2  justify-content-between">
                      <div class="demo01-asw">
                        <a href="{!!url('/menuaddon/'.$v->id)!!}">
                          <h2 class=" text-black elipsis-text font-opensans">{{$v->name}}</h2>
                        </a>
                        <h3 class="text-muted elipsis-text font-montserrat fooddesc">{{ $v->category_name }}</h3>
                        <h3 class="text-muted elipsis-text font-montserrat fooddesc">{{$v->description}}</h3>
                      </div>
                      <div class="demo02-asw">
                        <h3 class="text-theme font-montserrat">&#8377;{{$v->price}}</h3>
                        <p class="text-muted font-montserrat">Customizable</p>
                        <a href="{!!url('/menuaddon/'.$v->id)!!}" class="btn btn-theme-small btn-small addbutton font-montserrat">Add</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @endforeach
            @endif
          </div>

        </div>
      </div>
    </section>
  </div>


@endsection