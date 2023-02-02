<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>
        {{ config('app.name') }} | {{ Request::segment(2) }}
    </title>
    <meta content="{{ csrf_token() }}" name="csrf-token">
    {{--
    <link href="{{ asset('uploads/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    --}}
    <link href="{!! asset('assets/front/img/favicon.ico') !!}" rel="shortcut icon" type="image/x-icon"/>
    <!-- General CSS Files -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Template CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}"> -->
    <link href="{{ asset('admin/assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet">
    <!-- /global stylesheets -->
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
     <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/datetimepicker/jquery.datetimepicker.css') }}">
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script src="{{ asset('admin/assets/js/plugins/loaders/pace.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/core/libraries/popper.js') }}" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
    <script src="{{ asset('admin/assets/js/core/libraries/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/loaders/blockui.min.js') }}" type="text/javascript"></script>
    <!-- /core JS files -->
    <!-- Theme JS files -->
    <script src="{{ asset('admin/assets/js/plugins/tables/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/plugins/ui/dragula.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/selects/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/notifications/pnotify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/core/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/pages/extension_dnd.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/js/pages/datatables_responsive.js') }}" type="text/javascript"></script> --}}
    <script src="{{ asset('admin/assets/js/pages/components_modals.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/selects/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/pages/form_select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/forms/styling/uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/pages/form_inputs.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/ui/moment/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/pages/components_notifications_pnotify.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/assets/js/plugins/pickers/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{!! asset('admin/assets/js/plugins/pickers/pickadate/picker.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('admin/assets/js/plugins/pickers/pickadate/picker.time.js') !!}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/plugins.js') }}" id="appjs"></script>
    <script src="{{ \URL::to('assets/plugins/cropper/cropper.js') }}"></script>
    <script src="{{ \URL::to('assets/js/pvr_lite_cropper.js') }}" type="text/javascript"></script>
    <script src="{{ \URL::to('assets/plugins/caman/caman.full.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('admin/assets/js/plugins/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function(){
          ClassicEditor.create(document.querySelector('.Messagearea'))
          .then( editor => {
          } )
          .catch( error => {
          });
      });
     </script>    --}}
    @if(Request::is('*dashboard*'))
    <script src="https://code.highcharts.com/9.2.0/highcharts.js"></script>
    <script src="https://code.highcharts.com/9.2.0/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/9.2.0/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/9.2.0/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/9.2.0/modules/accessibility.js"></script>
    <script type="text/javascript"></script>
    @endif
    @yield('style')
    <style>
        #preloader {
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 99999;
            width: 100%;
            height: 100%;
            overflow: visible;
            background: #fff url('{{asset('/loading.gif')}}')  no-repeat center center;
        }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0; 
        }
    </style>
</head>
<body>
    <!-- Main navbar -->
    @include('layouts/backend/partials/header')
    <!-- /main navbar -->
    <!-- Page container -->
    <input type="hidden" value="{{ url('public/new_order_notification.mp3') }}" id="audio">
    <button type="button" class="play" onclick="callfunc()" style="display:none;">play</button>
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <div id="preloader">
            </div>
            <!-- Main sidebar -->
            @include('layouts/backend/partials/sidebar')
            <!-- /main sidebar -->
            <!-- Main content -->
            <div class="content-wrapper">
                @yield('page_header')
                <!-- Content area -->
                <div class="content">
                    @yield('content')
                    <!-- Footer -->
                    <div class="footer text-muted">
                        <a href="{!!\URL::to('')!!}">
                            {{ __('Copyright') }}
                        </a>
                        © {{ date('Y') }}.
                    </div>
                    <!-- /footer -->
                </div>
                <!-- /content area -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
    @yield('script')
    <script src="{!! asset('admin/assets/js/plugins/counter/textcounter.js') !!}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.min.js"></script>
    <script type="text/javascript">
        var socket = io('https://knosh.in:8891');
        socket.on('Alert',function(message){
            $('.play').trigger('click')
        });
        $('.play').click(function(){
            callfunc();
        });

        function callfunc(){
            var audiourl = $('#audio').val();
            var audio  = new Audio();
            audio.src = audiourl;
            audio.load();
            audio.play();
        }
    </script>
    <script type="text/javascript">
        /*$('.limitcount').textcounter({
            max: 255,
            countDown: true,
        });*/
        $('[data-toggle="tooltip"]').tooltip();
        const base_url='{!!url('')!!}';
        $(window).on('load', function() {
            $('#preloader').delay(100).fadeOut('slow',function(){$(this).remove();});
        });
        $(document).ready(function(){
            $('input[type="password"]').attr('autocomplete','new-password');
            $('.alert').fadeOut(5000);
            $(".close-admin-menu span").click(function(){
                $(".sidebar-xs-indicator").removeClass("sidebar-mobile-main");
            })
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.online-switch-asw input[type="checkbox"]').click(function(){
                if ($(this).prop("checked") == true) {
                    $(this).siblings("label").text("Online");
                    var mode ='open';
                } else if($(this).prop("checked") == false) {
                    $(this).siblings("label").text("Offline");
                    var mode ='close';
                }
                var v_id = $("#v_id").val();
                $.ajax({
                    type : 'PUT',
                    url : base_url+'/onandoffline_update',
                    data : {mode : mode, v_id : v_id},
                    success:function(data){
                        $('.modal').modal('hide');
                        var msg = JSON.parse(JSON.stringify(data)); 
                        $(".error-message-area").css("display","block");
                        $(".error-content").css("background","#d4d4d4");
                        $(".error-msg").html("<p style='color:red' class='mb-0'>"+msg.message+"</p>"); 
                        setTimeout(function(){location.reload()}, 1000);
                    },
                    error : function(err){ 
                        $('.modal').modal('hide');
                        var msg = err.responseJSON.message; 
                        $(".error-content").css("background","#d4d4d4");
                        $(".error-message-area").find('.error-msg').text(msg);
                        $(".error-message-area").show();
                    }
                });
            });

            $('.filter-orders-asw-menu').click(function(){
                $('.filter-orders-asw-backdrop').removeClass("d-none");
                $('.filter-orders-asw').addClass("active");
            })
            $('.filter-orders-asw-close ,.filter-orders-asw-backdrop').click(function(){
                $('.filter-orders-asw-backdrop').addClass("d-none");
                $('.filter-orders-asw').removeClass("active");
            })
        })
        <?php
            if(\Request::query('date') != ""){
                $dt  = explode(" - ",\Request::query('date'));
                $dt1 = $dt[0];
                $dt2 = $dt[1];
                $maxdate = date('Y-m-d',strtotime('+1 day'));  
            }else{
                $dt1 = date('Y-m-d', strtotime('-1 month'));
                $dt2 = date('Y-m-d');
                $maxdate = date('Y-m-d',strtotime('+10 days'));  
            }   
        ?>
        var startDate   = "{!! $dt1 !!}";
        var endDate     = "{!! $dt2 !!}";
        var maxdate     = "{!! $maxdate !!}";
        $('.daterange-basic').daterangepicker({
            applyClass  : 'bg-slate-600',
            cancelClass : 'btn-default',
            startDate   : startDate,
            endDate     : endDate,
            locale      : {
                format  : 'YYYY-MM-DD'
            },
            "maxDate"   : maxdate,
        }, function (start_date,end_date) {
            $('#start_date').val(start_date.format('YYYY-MM-DD')+' - '+end_date.format('YYYY-MM-DD'));
        });
        $("input[required],select[required],textarea[required]").parent().find('label').addClass("required");
    
        setTimeout (function(){
           $('.sidebar-category').animate({
            scrollTop: $("li.active").offset().top
        }, 100);
       }, 100)
    </script>
</body>
@yield('extra')
@stack('extra')
<!-- /theme JS files -->
</html>
