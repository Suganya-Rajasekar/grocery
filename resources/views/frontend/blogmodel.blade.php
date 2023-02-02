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
                <div class="close">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="comment-cont">
                <div class="modalfooddet mt-2 @if(Auth::check()) loged-in @endif">
                    <div class="foodstat">
                        <ul class="prep-time">
                            <li class="font-weight-bold">
                                <h3 class="float-left font-opensans font-weight-bold">{{strip_tags($menuinfo->name) }}</h3>
                            </li>
                        </ul>
                        <div class="details">
                            @if(isset($menuinfo->description) && $menuinfo->description!='')
                            {!! $menuinfo->description !!}
                            @endif
                            @if(isset($menuinfo->tags) && count($menuinfo->tags)>0)
                                    <?php 
                                    $tag_name    = array_column($menuinfo->tags, 'name');
                                    $implode_tag = implode(',',$tag_name);
                                    ?>
                            <p class="text-muted muted-font font-montserrat"><b>Tags:</b> {{ $implode_tag }}</p>
                            @endif
                            @if(isset($menuinfo->category) && count($menuinfo->category)>0)
                                    <?php 
                                    $cate_name    = array_column($menuinfo->category, 'name');
                                    $implode_cate = implode(',',$cate_name);
                                    ?>
                            <p class="text-muted muted-font font-montserrat"><b>Category:</b> {{ $implode_cate }}</p>
                            @endif
                        </div>
                    </div>
                  
                </div>
                
            </div>
        </div>
    </div>
</div>
@endif