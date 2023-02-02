@extends('main.app')
@section('content')
<!-- main wrapper -->
<section class="topsec terms-title  @if( isset($source) && $source == 'api' ) mt-30 @endif">
  <div class="container">
    <h1>{{ $page->title ?? '' }}</h1>
  </div>
</section>

<section class="success-pay terms-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="text-center">
        <div>
          <img src="{!! \URL::to('assets/front/img/oops-page-404-not-found.png') !!}" class="image-404">
        </div>
        <a href="{!!url('/')!!}" class="nav-link font-montserrat logintext btn text-white link active-menu ">Home page</a>
      </div>
    </div>
  </div>
</section>
<!-- end main wrapper -->
@endsection