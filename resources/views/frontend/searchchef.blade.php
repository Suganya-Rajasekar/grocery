@extends('main.app')
@section('content')
<style type="text/css">
  .tooltip {
    z-index: 100000000; 
  }
  .search-loader img{
    top: 50%;
    left: 50%;
    position: relative;
  }
</style>
<input type="hidden" value="1" id="page">
<section class="topsec"></section>
<section class="chefwithfood-search search-asw">
  <div class="background" style="margin-top: 111px;">

    <div class="searchdiv">
      <div class="input-group mb-3 align-items-center">
        <div class="input-group-prepend">
          <i class="fa fa-search input-group-text searchicon"></i>
        </div>
        <input type="text" value="{{ app('request')->input('q') }}" id="chef_fn_keyword" class="searchinputbox form-control font-montserrat" placeholder="Search Dish / Chef / Cuisine / Category / Tags" name="keyword" autocomplete="off">
        <div class="input-group-append">
          <button id="search_btn_head" class="hidden fas fa-chevron-right"></button>
        </div>
      </div>
      
      
      <!-- <input type="hidden" value="9.9551073" id="lat" name="lat"> -->
      <!-- <input type="hidden" value="78.1249667" id="lang" name="lang"> -->
      
<!--  <a class="searchclear">clear</a>
<a class="searchclose tohome" onclick="window.history.back();">
<span class="close searchcloseicon"><i class="fa fa-times"></i></span>
<span class="esc"> ESC</span>
</a> -->
</div>

</div>
<div class="search-loader" style="display:none;"><img src="{{ url('searchloader.gif') }}"></div>
<div class="chef-container-fluid search_result">
    <div class="chefslist-s container-fluid">
        <div class="chef_result">
            <div class="text-center">
                <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="290px; height: auto;">
               {{--  <h4 class="font-montserrat">No Result found!..</h4> --}}
            </div>
        </div>
        <div class="paginate"></div>
    </div>
    <button class="btn btn-default col-md-12 load"  type="button" name="searchchef" style="display:none;">Load More</button>
</div>
</div>

</section>
@endsection
@section('script')
{{-- <script src="{{ asset('assets/front/js/details.js') }}"></script> --}}
<script src="{{ asset('assets/front/js/main.js') }}"></script>
<script>
  function timeupdate(id){
    var tim = $('#time_slot_'+id).val();
    $('.datatime'+id).attr("data-myval",tim);
  }   

</script>
<script type="text/javascript">
  $(document).ready(function() {

    $('#search_btn_head').click(function () {
      var title = $("#chef_fn_keyword").val(); 
//$(".searchinputbox:text").val(title);
if(title != ''){
  var str = window.location.search;
  str = replaceQueryParam('q',title,str);
  if (history.pushState) {
    window.history.pushState({path:window.location.pathname + str},'',window.location.pathname + str);
  }
  getSearchDishResult();
}
});
    var title = $("#chef_fn_keyword").val(); 
//$(".searchinputbox:text").val(title);
if(title != ''){
  var str = window.location.search;
  str = replaceQueryParam('q',title,str);
  if (history.pushState) {
    window.history.pushState({path:window.location.pathname + str},'',window.location.pathname + str);
  }
  getSearchDishResult();
}
});
  $(document).on('keyup','.searchinputbox',debounce(function(){
    var chefname = $(this).val(); 
    if(chefname.length >= 3){
    $(".search_result").hide();  
    $('.search-loader').show(); 
      var str = window.location.search;
      str = replaceQueryParam('q',chefname,str);
      if (history.pushState) {
        window.history.pushState({path:window.location.pathname + str},'',window.location.pathname + str);
      }
     getSearchDishResult();
    }
  },500));
  $(document).on('click','.load',function(){
    var offsetValue = $('#page').val();
    offsetValue = parseInt(offsetValue)+1; 
    $("#page").val(offsetValue);
    var page = offsetValue;
    getSearchDishResult(page);
  });

  function getSearchDishResult(pages){
    var str = window.location.search;
    var page = pages;   
    $.ajax({
      url : base_url+"searchchef"+str,
      type : 'get',
      data : {"page":page},
//dataType : "json",
success : function(res){
    if(page) {
        $('.paginate').append(res.html);
    } else {
        $(".chef_result").html(res.html);
        $('.load').show();
    }
  $('.search_food').owlCarousel({
    loop:false,
    margin:10,
    nav:false,
    dots:false,
    responsive:{
      0:{
        items:1
      },
      550:{
        items:2
      },
      850:{
        items:3
      },
      1400:{
        items:5
      }
    }
  });

  var date = new Date();
  date.setDate(date.getDate()+1);
  var enddate = new Date();
  enddate.setDate(enddate.getDate()+7);

  $( ".datepicker" ).datepicker({
    format: 'yy-mm-dd',
    startDate: date,
    endDate: enddate,
    autoclose: true,
    todayHighlight: true,
  }).on('changeDate', function(date){
    var date=$(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
    var id = $(this).attr('data-id');
    $('#future_date_'+id).val(date);
  });
  $('[data-toggle="tooltip"]').tooltip();
// outside click can't close modal popup
$(".modal").modal({
  show: false,
  backdrop: 'static'
});
},
complete:function(){
  $('.search-loader').hide();
    $(".search_result").show();
}
})
  }

  function replaceQueryParam(param, newval, search) {
    var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
    var query = search.replace(regex,'$1').replace(/&$/,'');
    return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
  }
</script>
@endsection

