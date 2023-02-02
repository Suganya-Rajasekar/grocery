<?php //echo "<pre>";print_r($menuinfo);exit;?>
@if($menuinfo)
           
<div class="row">
          <div class="col-md-6">
            <div class="modalimg">
              <img src="{{$menuinfo->image}}" alt="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="close">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modalfooddet">
              <div class="foodstat">
            <ul class="prep-time">
                <li class="font-weight-bold">
                  <span class="float-left">{{$menuinfo->name}}</span>
                  <span class="float-right">${{$menuinfo->price}}</span>
                </li>
                <li>
                  <span class="float-left">Preperation time</span>
                  <span class="float-right">{{$menuinfo->preparation_time_text}}</span>
                </li>
              </ul>
              <div class="details">
                <h4>Details</h4>
                <p class="text-muted muted-font">{{ strip_tags($menuinfo->description) }}</p>
              </div>
            </div>
            @if($menuinfo->comments)
            <div class="commend-food">
              <div class="custcomment">
                <div class="customer-image float-left mr-3">
                  <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&usqp=CAU" alt="">
                </div>
                <div class="customer-comment over-hid">
                  <div class="custname-likes  ">
                    <div class="d-flex">
                    <div class="float-left">
                      <h4 class="font-weight-bold">lilly</h4>
                      <p class="text-secondary"> </p>
                      <ul class="comment-stat text-muted">
                        <li>
                          <span class="text-muted">12hr</span>
                        </li>
                        <li>
                          <span class="text-muted">12 Likes</span>
                        </li>
                        <li>
                          <span class="text-muted">reply</span>
                        </li>
                      </ul>
                      <div class="viewreplies text-muted my-2">
                        --------<a href="#" class="text-muted font-weight-bold">View reply(2)</a>
                      </div>
                    </div>
                    <div class="float-right">
                      <i class="fa fa-heart"></i>
                    </div>
                  </div>
                  <div class="cust-reply d-flex">
                  <div class="customer-image float-left mr-3">
                  <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&usqp=CAU" alt="">
                </div>
                <div class="float-left">
                      <h4 class="font-weight-bold">lilly</h4>
                      <p class="text-secondary"></p>
                      <ul class="comment-stat text-muted">
                        <li>
                          <span class="text-muted">12hr</span>
                        </li>
                        <li>
                          <span class="text-muted">12 Likes</span>
                        </li>
                        <li>
                          <span class="text-muted">reply</span>
                        </li>
                      </ul>
                    </div>
                </div>
                  </div>

                </div>
                
              </div>
            </div>
            @endif
              @if (Auth::check())
              <!-- <form name="myForm" action="{{url('commentsend')}}" method="POST">
               <input type="hidden" name="food_id" value="{{$menuinfo->id}}">
               <input type="text" name="comment" value="">
               <input type="submit" name="submit" value="submit">  
              </form> --> 
              @endif 
            </div>
            <div class="chefdetails overflow-hidden mt-3">
            <div class="float-left">
              <ul>
                <li><a href="javascript:void(0)" onclick="updateFavorites( {{ $menuinfo->id }} )"><span class="@if($menuinfo->is_favourites == 1) fa fa-heart @else fa fa-heart-o @endif"></span></a></li>
                <li><a href="#"><span class="fa fa-comment"></span></a></li>
                <li><a href="#"><span class="fa fa-share-alt"></span></a></li>
              </ul>
            </div>
            
          </div>
          </div>
        </div>
        @endif