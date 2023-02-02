<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css"> --}}

    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap-grid.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap-reboot.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/css/colors.css') !!}"  rel="stylesheet" id="themecolor"/>
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet"/>
    @if(Request::is('addon*'))
    <link href="{!! asset('assets/plugins/select2/css/select2.min.css') !!}" rel="stylesheet"/>
    @endif
    @if(Request::is('testimonials*') || Request::is('category*') || Request::is('translate*') || Request::is('addon*'))
    <link href="{!! asset('assets/plugins/SmartWizard/css/smart_wizard.css') !!}" rel="stylesheet"/>
    <style type="text/css">
        li{
            color: black;
            font-weight: 100;
            font-size: 15px;
        }
    </style>
    @endif
    <link href="{!! asset('assets/plugins/lightbox/css/lightbox.css') !!}" rel="stylesheet"/>
    <link rel="stylesheet" href="{!! asset('assets/plugins/amcharts/export.css') !!}" type="text/css" media="all"/>
    <link href="{!! asset('assets/plugins/jquery-jvectormap/jquery-jvectormap.css') !!}" rel="stylesheet"/>
    <link href="{!! asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') !!}" rel="stylesheet"/>

    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css"> -->

    <!-- iCheck -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css"> -->

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"> -->

    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->
    @yield('css')
</head>

<body class="skin-blue">
    @if (!Auth::guest())
    <div class="wrapper">
        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Main Header -->
        <div class="main-panel">
            <!-- Header Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-minimize">
                            <button id="minimizeSidebar" data-color="purple" class="btn btn-fill btn-round btn-icon d-none d-lg-block">
                                <i class="fa fa-ellipsis-v visible-on-sidebar-regular"></i>
                                <i class="fa fa-navicon visible-on-sidebar-mini"></i>
                            </button>
                        </div>
                    </div>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="nav navbar-nav">
                            <li>
                                <form class="navbar-form navbar-left navbar-search-form d-none d-lg-block" role="search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <span class="input-group-addon"><i class="icons icon-magnifier p-r-10"></i></span>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Content Wrapper. Contains page content -->
            <div class="content dashboard_v1">
                @yield('content')
            </div>
        </div>
        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2016 <a href="#">Company</a>.</strong> All rights reserved.
        </footer>
    </div>
    @else
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- jQuery 3.1.1 -->
    <script type="text/javascript">
        var first_login = '{{\Auth::user()->first_login}}';
        var csrf_token = '{{ csrf_token() }}';
        var base_url = "<?php echo URL::to('/').'/'; ?>";
    </script>
    <script src="{!! asset('assets/plugins/jquery/jquery-3.2.1.min.js') !!}" type="text/javascript"></script>
    @if(Request::is('testimonials*') || Request::is('category*') || Request::is('translate*')  || Request::is('addon*'))
    <script src="{!! asset('assets/plugins/SmartWizard/js/jquery.smartWizard.js') !!}"></script>
    <script src="{!! asset('assets/js/pvr_lite_wizard.js') !!}"></script>
    @endif
    @if(Request::is('addon*'))
    <script src="{!! asset('assets/plugins/select2/js/select2.min.js') !!}"></script>
    <script src="{!! asset('assets/js/pvr_lite_select2.js') !!}" type="text/javascript"></script>
    @endif
    <script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/plugins/pace/pace.min.js') !!}"></script>
    <script src="{!! asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') !!}"></script>
    <script src="{!! asset('assets/js/pvr_lite_app.js') !!}" id="appjs"></script>
    <script src="{!! asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}"></script>
    <script src="{!! asset('assets/plugins/typeit/typeit.js') !!}"></script>
    <script src="{!! asset('assets/plugins/countup/countUp.min.js') !!}"></script>
    <script src="{!! asset('assets/plugins/lightbox/js/lightbox.min.js') !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
    <script src="{!! asset('assets/front/js/main.js') !!}" id="appjs"></script>
    @stack('scripts')
    @include('layouts.modal')
    <script type="text/javascript">
        $(document).ready(function(){
            if(first_login == 1)
            {
                $('#first_login').modal('show');
            }
            $('.alert').fadeOut(5000);
        });

        $('.AssignEmployee').click(function(){
            var Bookid = $(this).attr('data-id');
            $('#AssignEmployee').find('#book_id').val(Bookid);
            $('#AssignEmployee').modal('show');
        });
        $('.first_login_yes').click(function(){
            $(this).hide();
            $('#password-change').show();
        });

        $("#password-change").validate({
            onkeyup: false,
            onclick: false,
            onfocusout: false,
            rules: {
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
                password: {
                    required: true,
                    minlength: 6,
                },
            },
            messages: {
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long",
                    equalTo: "Password Mismatch"
                },
            },
            submitHandler: function(form) {
                var form_data = new FormData($("#password-change")[0]);
                $.ajax({
                    type    : 'POST',
                    dataType: 'json',
                    url     : base_url + "password-change",
                    data    : form_data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>
</body>
</html>