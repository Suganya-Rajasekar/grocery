@extends('main.app')

@section('content')
<section class="register_page login_page">
    <div class="container-fluid">
        <div class="row">
         <div class="col-md-6">
            <div class="image_section">
                <img src="assets/front/img/register.png">
                 <h3>Start tracking your <br> subscriptions now!</h3>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="title_form"><a href="signin"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
 Forgot Password</h2>
            <form  id="forgot_form"  action="{{ url('/password/email') }}" method="POST">
                @csrf
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <div class="row m-0">
                <div class="col-md-12 p-0">
                        <div class="form-group">
                            <label for="usr">Email:</label>

                            <input type="text" class="form-control" name="email" id="email" value="" required="">
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                </div>


                <button class="btn_submit" type="submit">Send Email Reset Link</button>    


            </form>
        </div>
    </div>
</div>
</section>

@endsection


