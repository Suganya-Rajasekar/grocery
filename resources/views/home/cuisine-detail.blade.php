@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
 <input type="hidden" id="Page" value='1'>
 {{-- <?php print_r(\Request::has('pageNumber'));exit(); ?> --}}
  {{-- @if(isset($alloffer->promos))
    <div class="carousel">
      <div class="container-fluid">
        <div class="row pt-sm-50 rest_grocery m-0 owl-carousel owl-carousel-cui owl-loaded owl-drag" id="owlcarousel-rest">
          <div class="car-asw">
            <div class="owl-carousel top-area-car owl-theme">
              @foreach($alloffer->promos as $off_ke => $off_val)
              <div class="item">
                <div>
                  <a href="{!!url('/chefoffer/'.$off_val->id.'/'.\Request::segment(4).'/'.\Request::segment(5) )!!}" ><img src="{{$off_val->image}}" alt=""/></a>
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

  <div class="container-fluid  ">

    <div class="searchbyfood">
      <div class="">
        {{-- @if(count($cuisine) > 0) --}}
          <div class="">
            {{-- <div class=" area-asw-filter">      
              <!-- <i class="fa fa-angle-double-left" style="font-size:24px" onclick="seeexplore('cuisines')"></i> -->
              <a class="font-montserrat btn btn-theme font-weight-normal" onclick="seeexplore('cuisines')">Back to cuisine</a>
              <section class="filterby mb-5 d-flex justify-content-between align-items-center">
                <input type="hidden" id="hid_lat" value="0">
                <input type="hidden" id="hid_lang" value="0">
                <div class="form-group">
                  <select class="form-control font-montserrat" name="area" id="filter">
                    <option  value="cuisines" data-id="explore">cuisines</option>
                    <option  value="popularNearYou">popular Near You</option>
                    <option  value="topRatedChefs">Top Rated Chefs</option>
                    <option  value="celebrityChefs">Celebrity Chefs</option>
                    <option  value="nearByChefs">Near by Chefs</option>
                    <option  value="7" data-id="explore">Snacks</option>
                    <option  value="8" data-id="explore">Dessert</option>
                    <option  value="9" data-id="explore">Bakery</option>
                  </select>
                </div>
              </section>
            </div> --}}

           @include('home.cuisine_page')

          </div>

        {{-- @endif --}}
        <div class="paginate"></div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
$(document).on('click', '.loadModulecuisine', function(){
            var curURL      = window.location.href;
            var segments    = curURL.split( '/' );
            var module      = $(this).attr('name');
            var offsetValue = pagecount;
            offsetValue     = parseInt(offsetValue )+1;
            pagecount       = offsetValue;
            var page       = {pageNumber:  offsetValue};
            lat             = localStorage.getItem("lat");
            lang = localStorage.getItem("lang");
            $.ajax({
                url     : curURL,
                type    : "get",
                dataType: "json",
                async   : true,
                data    : {pageNumber:  offsetValue},
                success : function(data) {
                    $(".loadModulecuisine").css("display","none");
                    $(".paginate").append(data.html); 
                     $('.see-more').owlCarousel({
                        loop:false,
                        margin:10,
                        nav:false,
                        dots:false,
                        responsive:{
                            0:{
                                items:1
                            },
                            600:{
                                items:2
                            },
                            850:{
                                items:3
                            },
                            1400:{
                                items:5
                            }
                        }
                    })

                }
            });
        });
</script>
@endsection