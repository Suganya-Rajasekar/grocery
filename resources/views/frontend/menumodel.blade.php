@if($menuinfo)
<div class="row">
    <div class="col-lg-6 col-md-5">
        <div class="modalimg d-flex h-100 align-items-center">
            <img src="{{$menuinfo->image}}" alt="">
        </div>
    </div>
    <div class="col-lg-6 col-md-7">
        <div class="h-100">
            <div class="" style="overflow: hidden;">
                <div class="close">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="comment-cont d-block d-md-flex flex-column justify-content-center">
                <div class="modalfooddet mt-2 @if(Auth::check()) loged-in @endif">
                    <div class="foodstat">
                        <ul class="prep-time">
                            <li class="font-weight-bold">
                                <h3 class="float-left font-opensans font-weight-bold">{{$menuinfo->name}}</h3>
                                <h3 class="float-right font-montserrat font-weight-bold">&#8377;{{$menuinfo->price}}</h3>
                            </li>
                            {{-- <li>
                                <span class="float-left font-montserrat">Delivers {{strtolower($menuinfo->preparation_time_text)}}</span>
                            </li> --}}
                        </ul>
                        <div class="details">
                            <p class="text-dark muted-font font-montserrat">{{ strip_tags($menuinfo->description) }}</p>
                        </div>
                    </div>
                    @if(count($menuinfo->comments) > 0)
                    <h4 class="font-montserrat">Comments</h4>
                    @foreach($menuinfo->comments as $comm_ke => $comm_val)
                    <div class="commend-food">
                        <div class="custcomment">
                            <div class="customer-image float-left mr-3">
                                <img src="{{ $comm_val->userinfo->avatar }}" alt="">
                            </div>
                            <div class="customer-comment over-hid">
                                <div class="custname-likes  ">
                                    <div class="d-flex">
                                        <div class="float-left">
                                            <h4 class="font-weight-bold font-opensans">{{ $comm_val->userinfo->name }}</h4>
                                            <p class="text-secondary font-montserrat"> {{ $comm_val->comment }}</p>
                                            <ul class="comment-stat text-muted">
                                                <li>
                                                    <span class="text-muted font-montserrat">{{ $comm_val->day }}</span>
                                                </li>
                                                <li>
                                                    <span class="text-muted font-montserrat">{{ $comm_val->like_count }} Likes</span>
                                                </li>
                                                <li>
                                                    <span class="text-muted font-montserrat reply-button">reply</span>
                                                </li>
                                            </ul>
                                        <div class="post-comment reply_div" style="margin-top: 20px;">
                                        <form class="reply_comment">
                                            @csrf
                                            {{ method_field('PATCH') }}
                                            <input type="hidden" name="food_id" id="food_id" value="{{$menuinfo->id}}">
                                            <input type="hidden" name="c_id" id="c_id" value="{{$comm_val->id}}">
                                            <input type="hidden" name="auth" id="auth" value="{{ isset(\Auth::user()->id) ? \Auth::user()->id : ''}}">
                                            <div class="d-flex justify-content-evently">
                                                <textarea name="comment" id="comment" placeholder="Add a comment" style="width:100%;border: none;" required></textarea>
                                                <input type="button" name="submit" value="Post" id="replypost" style="color: red;background-color: white;border: none ;margin-bottom: 20px;">
                                            </div>
                                        </form>
                                    </div> 
                                            <div class="viewreplies text-muted mt-1 mb-2">
                                                <a href="#" class="text-muted font-montserrat">{{ $comm_val->reply_count_text }}</a>
                                            </div>
                                        </div>
                                        <div class="float-right" id="comment_like">
                                            {{-- <i class="fa fa-heart like"></i> --}}
                                            <i class="like @if($comm_val->like_count != 0) fa fa-heart @else fa fa-heart-o @endif" data-cid="{{ isset($comm_val->id) ? $comm_val->id : ''}}" data-foodid="{{ isset($menuinfo->id) ? $menuinfo->id : '' }}" data-auth="{{ isset(\Auth::user()->id) ? \Auth::user()->id : ''}}"></i> {{-- unlike --}}
                                        </div>
                                    </div>
                                    @if($comm_val->replyinfo)
                                    @foreach($comm_val->replyinfo as $reply_ke => $reply_val)
                                    <div class="replies">
                                    <div class="cust-reply d-flex">
                                        <div class="customer-reply-img float-left mr-3">
                                            <img src="{{ $reply_val->userinfo->avatar }}" alt="">
                                        </div>
                                        <div class="float-left">
                                            <h4 class="font-weight-bold font-opensans">{{ $reply_val->userinfo->name }}</h4>
                                            <p class="text-secondary font-montserrat"> {{ $reply_val->comment }}</p>
                                            <ul class="comment-stat text-muted">
                                                <li>
                                                    <span class="text-muted font-montserrat">12hr</span>
                                                </li>
                                                <li>
                                                    <span class="text-muted font-montserrat">{{ $reply_val->like_count_text }}</span>
                                                </li>
                                                {{-- <li>
                                                    <span class="text-muted font-montserrat">Reply</span>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="chefdetails chefdetail_s overflow-hidden mt-3">
                    <div class="mb-4">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" onclick="updateFavorites( {{ $menuinfo->id }})"><span class="@if($menuinfo->is_favourites == 1) fa fa-heart @else fa fa-heart-o @endif"></span>
                                </a>
                            </li>
                        </ul>
                        <p class="font-montserrat">{!! $menuinfo->likes_count_text !!}</p>
                    </div>
                    @if (Auth::check())
                    <div class="post-comment">
                        <form id="commentform" data-form="comment">
                            @csrf
                            {{ method_field('POST') }}
                            <input type="hidden" name="food_id" value="{{$menuinfo->id}}">
                            <div class="d-flex justify-content-evently">
                                <textarea name="comment" id="" placeholder="Add a comment" style="width:100%;border: none;" required></textarea>
                                <input type="submit" name="submit" value="Post">
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<script src="{{ URL::to('/assets/front/js/details.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.reply_div').hide();
        $('.replies').hide();
    });
    $(document).on('click','.viewreplies',function(){
        $('.replies').toggle();
    });
    $(document).on('click','.reply-button',function(){
        $(this).closest('.custname-likes').find('.reply_div').toggle();
    });
    $(document).on('click','.like',function(){
        var c_id     = $(this).attr('data-cid');
        var food_id  = $(this).attr('data-foodid');
        var icon     = $(this).closest('#comment_like').find('#like');
        var authuser = $(this).attr('auth');
        if(authuser){
            $.ajax({
                url  : base_url+'commentlike',
                type : 'PUT',
                data:{food_id:food_id,c_id:c_id},
                success:function(res){
                    var msg = JSON.parse(JSON.stringify(res)); 
                    if(res.comment_like_detail.c_id){
                     icon.removeClass('fa-heart-o');
                     icon.addClass('fa-heart');
                 } else {
                     icon.removeClass('fa-heart');
                     icon.addClass('fa-heart-o'); 
                 }
                 toast(msg.message, 'Success!', 'success');
             },
             error : function(err){
                var msg = err.responseJSON.message; 
                $(".error-content").css("background","#d4d4d4");
                $(".error-message-area").find('.error-msg').text(msg);
                $(".error-message-area").show();
            }
         }); 
        } else {
            self.location=baseurl+"/login";
        } 

    });
</script>    