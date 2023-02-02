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
    <div class="row">
      <div class="info">{!! $page->content ?? '' !!}</div>
    </div>
  </div>
</section>
<!-- end main wrapper -->
@endsection