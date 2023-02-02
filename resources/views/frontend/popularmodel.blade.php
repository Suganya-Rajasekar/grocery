@if($menuinfo)
<div class="row align-items-center">
    <div class="col-lg-6 col-md-5">
        <div class="modalimg">
            <img src="{{$menuinfo->image}}" alt="">
        </div>
    </div>
    <div class="col-lg-6 col-md-7">
        <div class="h-100">
            <div class="" style="overflow: hidden;">
                <div class="close close-cross">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="comment-cont">
                <div class="modalfooddet mt-2 @if(Auth::check()) loged-in @endif">
                    <div class="foodstat">
                        <ul class="prep-time">
                            <li class="font-weight-bold">
                                <h3 class="float-left font-opensans font-weight-bold">{{$menuinfo->name}}</h3>
                            </li>
                        </ul>
                        <div class="details">
                            @if($menuinfo->type=='desc')
                            <p class="text-muted muted-font font-montserrat">{{ strip_tags($menuinfo->description) }}</p>
                            @elseif($menuinfo->type=='video')
                            {{-- <iframe src="{{$menuinfo->video}}" height="300" width="600"></iframe> --}}
                            <video controls autoplay>
                                <source src="{{$menuinfo->video}}" type="video/mp4">
                            </video>
                            @endif
                        </div>
                    </div>
                  
                </div>
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#popularmodal').on('hide.bs.modal', function (e) {
            var localplayer = $('.modalfooddet .foodstat .details video');
            localplayer.trigger('pause');      
        })
    });
</script>


@endif