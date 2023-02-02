
@extends('main.app')
@section('content')
<!-- main wrapper -->
<section class="topsec terms-title">
	<div class="container">
		<h1></h1>
	</div>
</section>

<section class="success-pay terms-content">
	<div class="container">

		<div class="info">

			@include('flash::message')


			<div class="contactus_bg">
				<div class="container margin_60">
					{{-- <div class="row mb-5"> --}}
						{{-- <div class="col-md-6">
							<div class="box_align mb-3">
								<h3 class="form_title_add">
									Office Address
								</h3>
								<p>504, Paras Trinity, Sector 63, Gurgaon</p>
							</div>
						</div> --}}
						<div class="d-flex justify-content-center">
							<div class="box_align mb-3 col-md-6">
								<h3 class="form_title_add">
									Contact us at
								</h3>
								<p>support@knosh.in</p>
							</div>
						</div>
					{{-- </div> --}}
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 margin-tb-20 mt-3 contact-fill-form">
							<div class="form_title">
								Have Feedback?
							</div>
							<!-- -->
							<div class="contact-form">

								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12 margin-tb-20">
										<ul class="parsley-error-list">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
										{!! Form::open(array( 'id'=> 'contactForm' , 'url'=>'home/contact' , 'method'=>'post')) !!}
										<div class="form-group name">
											<label for="name">Your Name</label> {!! Form::text('name', null,array( 'required'=>'true','class'=>'form-control')) !!}
										</div>
									</div>


									<div class="col-md-6 col-sm-6 col-xs-12 margin-tb-20">
										<div class="form-group email">
											<label for="email">Your Email</label> {!! Form::email('sender', null,array( 'required'=>'true','class'=>'form-control' )) !!}</div></div></div>


											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-12 margin-tb-20">
													<div class="form-group mobile">
														<label for="mobile">Your MobileNumber</label>

														<input type="text" name="phone_number" class="form-control chekphone" placeholder="" maxlength="10">
													</div></div>

													<div class="col-md-6 col-sm-6 col-xs-12 margin-tb-20">
														<div class="form-group email">
															<label for="subject">Subject</label> {!! Form::text('subject', null,array( 'required'=>'true email','class'=>'form-control' )) !!}</div></div>
														</div>

														<div class="row">
															<div class="col-md-6 col-sm-6 col-xs-12 margin-tb-20">
																<div class="form-group message"><label for="message">Your Message</label> {!! Form::textarea('message',null,array( 'required'=>'true','class'=>'form-control' )) !!}</div>
															</div></div>
															<button class="btn btn-common" type="submit">Send</button>
															<input name="redirect" type="hidden" value="contact-us" /> {!! Form::close() !!}

														</div>

														<div class="gap">&nbsp;</div>
													</div>
												</div>
											</div>
										</div>
										<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

										<script type="text/javascript">

											$(document).on('keypress','.chekphone',function(e){
												if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
													$("#errmsg").html("Digits Only").show().fadeOut("slow");
													return false;
												}
											});
	// $("#contactForm").on('submit', function(){
	// 	alert('x');
	// });

</script>




</div>

</div>
</section>
<!-- end main wrapper -->
@endsection

