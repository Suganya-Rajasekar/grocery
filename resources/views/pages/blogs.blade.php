
@extends('main.app')
@section('content')

<section class="blog-media-sec">
	<div class="py-lg-5 py-sm-4">
		<div class="container">
			<div class="row">
				@foreach($mediapress as $key=>$val)
				@if($val->status == 'active')
				<div class="col-xl-2 col-lg-4 col-6">
					<a class="d-block blog-content p-2 mb-lg-5 mb-4" @if($val->media_type == 'description') data-toggle="modal" data-target="#mediaBlog{{ $val->id }}" href="javascript:void(0)"@elseif($val->media_type == 'external_link') href="{{ $val->description }}" @endif>
						<div class="img-sec">
							<img src="{{ $val->image }}">
						</div>
						<div>
							{{-- <h4>{{ $val->name }}</h4> --}}
							{{-- <span>{{ $val->created_at->format('d-M-Y') }}</span> --}}
							{{-- <p>{{ strip_tags($val->description) }}</p> --}}
						</div>
						{{-- <button class="btn" @if($val->media_type == 'description') data-toggle="modal" data-target="#mediaBlog{{ $val->id }}" @elseif($val->media_type == 'external_link') onclick="window.location=('{{ $val->description }}');" @endif>Read more</button> --}}
					</a>
				</div>
				@endif
				@endforeach
			</div>
		</div>
	</div>
</section>

@foreach($mediapress as $key=>$val)
<div class="modal fade mediaSection" id="mediaBlog{{ $val->id }}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <button type="button" class="close text-right p-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-body pt-5 pb-4 px-3">
            <div class="media-content row align-items-start">
                <div class="col-lg-5 col-md-5">
                    <img src="{{ $val->image }}">
                    <h3 class="article-title text-md-left text-center">{{ $val->name }}</h3>
                    <span class="article-date text-md-left text-center d-block">{{ $val->created_at->format('d-M-Y') }}</span>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="article-content pr-1">
                        <p>{{ strip_tags($val->description) }}</p>
                    </div>
                </div>  
            </div>
        </div>
    </div>
  </div>
</div>
@endforeach

@endsection
