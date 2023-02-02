@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
  <div class="carousel">
   <div class="container-fluid">
    <div class="row pt-sm-50 rest_grocery m-0 owl-carousel owl-carousel-cui owl-loaded owl-drag" id="owlcarousel-rest">
      <div class="car-asw">
        <div class="owl-carousel top-area-car owl-theme">
          <div class="item">
            <div>
              <img src="https://static.vecteezy.com/system/resources/thumbnails/001/254/896/small_2x/creative-minimal-offer-special-food-banner-for-social-media.jpg" alt=""/>
            </div>
          </div>
          <div class="item">
            <div>
              <img src="https://image.freepik.com/free-psd/special-food-menu-offer-social-media-post-banner-design_268949-21.jpg" alt=""/>
            </div>
          </div>
          <div class="item">
            <div>
              <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/food-deliver-%26-offer-ads-design-template-664c0400c6b3d38642ed37f0f1e2134c_screen.jpg?ts=1591836204"/>
            </div>
          </div>
          <div class="item">
            <div>
              <img src="https://image.freepik.com/free-vector/restaurant-food-offer-special-social-menu-media-post-colorful-abstract-premium-template_171965-408.jpg"/>
            </div>
          </div>
      </div>
      </div>
    </div>

  </div>
</section>
<div class="container-fluid area-asw-filter">      
  <section class="filterby my-5 mx-sm-5 mx-0 ">
    <ul class="row filter-checkbox">
      <li class="col-xl-3 col-lg-4 col-6">
        <div class=" custom-checkbox">
          <label class="container-check" for="">Popular near you
            <input type="checkbox" class="custom-control-input">
            <span class="checkmarks"></span>
          </label>
        </div> 
      </li>
      <li class="col-xl-3 col-lg-4 col-6">
        <div class=" custom-checkbox">
          <label class="container-check" for="">Top rated chefs
            <input type="checkbox" class="custom-control-input">
            <span class="checkmarks"></span>
          </label>
        </div> 
      </li>
      <li class="col-xl-3 col-lg-4 col-6">
        <div class=" custom-checkbox">
          <label class="container-check" for="">Sponsered chefs

            <input type="checkbox" class="custom-control-input">
            <span class="checkmarks"></span>
          </label>
        </div> 
      </li>
      <li class="col-xl-3 col-lg-4 col-6">
        <div class=" custom-checkbox">
          <label class="container-check" for="">Near by chefs

            <input type="checkbox" class="custom-control-input">
            <span class="checkmarks"></span>
          </label>
        </div> 
      </li>
    </ul>
  </section>
</div>

<div class="container-fluid">
  <button type="button" class="btn " data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
  </button> 
  
  <div class="modal fade my-mod-1" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header pinkbox">
          <h5 class="modal-title" id="exampleModalLabel">
            <span class="foodname">Customized biriyani</span>
            <div class="d-flex">
              <div class="itembuttn">
                <span>-</span>
                <h5>1</h5>
                <span>+</span>
              </div>
              <div class="text-theme">
                20&#8377;
              </div>
            </div>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="">
            <div class="modal-body-cont">
              <div class="addon-sec over-hid">
                <div class="modalbody-head">
                  <h5 class="text-black font-weight-bold">Addons</h5>
                </div>
                <div class="cont">
                  <ul class="over-hid ord-mod">
                    <li>
                      <div class="float-left">
                        <div class=" custom-checkbox">
                         <label class="container-check" for="">Biriyani
                           <input type="checkbox" class="custom-control-input">
                           <span class="checkmarks"></span>
                         </label>
                       </div>
                     </div>
                     <div class="float-right">
                      <div class="foodprice">20&#8377;</div>
                    </div>
                  </li>
                  <li>
                    <div class="float-left">
                      <label class="radio-container">One
                        <input type="radio" checked="checked" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="float-right">
                      <div class="foodprice">20&#8377;</div>
                    </div>
                  </li>
                  <li>
                    <div class="float-left">
                      <label class="radio-container">One
                        <input type="radio" checked="checked" name="radio">
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="float-right">
                      <div class="foodprice">20&#8377;</div>
                    </div>
                  </li>
                  <li>
                    <div class="float-left">
                      <div class=" custom-checkbox">
                       <label class="container-check" for="">Biriyani
                         <input type="checkbox" class="custom-control-input">
                         <span class="checkmarks"></span>
                       </label>
                     </div>
                   </div>
                   <div class="float-right">
                    <div class="foodprice">20&#8377;</div>
                  </div>
                </li>
                <li>
                  <div class="float-left">
                    <div class=" custom-checkbox">
                     <label class="container-check" for="">Biriyani
                       <input type="checkbox" class="custom-control-input">
                       <span class="checkmarks"></span>
                     </label>
                   </div>
                 </div>
                 <div class="float-right">
                  <div class="foodprice">20&#8377;</div>
                </div>
                  </li>
                </ul>
              </div>
            </div>
        <div class="addon-sec over-hid">
          <div class="modalbody-head">
            <h5 class="text-black font-weight-bold">Evening</h5>
          </div>
          <div class="cont" style="line-height: 2;">
            <ul class="over-hid ord-mod">
              <li>
                <div class="float-left">
                  <div class=" custom-checkbox">
                   <label class="container-check" for="">Biriyani
                     <input type="checkbox" class="custom-control-input">
                     <span class="checkmarks"></span>
                   </label>
                 </div>
               </div>
               <div class="float-right">
                <div class="foodprice">20&#8377;</div>
              </div>
            </li>
            <li>
              <div class="float-left">
                <label class="radio-container">One
                  <input type="radio" checked="checked" name="radio">
                  <span class="checkmark"></span>
                </label>
              </div>
              <div class="float-right">
                <div class="foodprice">20&#8377;</div>
              </div>
            </li>
            <li>
              <div class="float-left">
                <label class="radio-container">One
                  <input type="radio" checked="checked" name="radio">
                  <span class="checkmark"></span>
                </label>
              </div>
              <div class="float-right">
                <div class="foodprice">20&#8377;</div>
              </div>
            </li>
            <li>
              <div class="float-left">
                <div class=" custom-checkbox">
                 <label class="container-check" for="">Biriyani
                   <input type="checkbox" class="custom-control-input">
                   <span class="checkmarks"></span>
                 </label>
               </div>
             </div>
             <div class="float-right">
              <div class="foodprice">20&#8377;</div>
            </div>
          </li>
          <li>
            <div class="float-left">
              <div class=" custom-checkbox">
               <label class="container-check" for="">Biriyani
                 <input type="checkbox" class="custom-control-input">
                 <span class="checkmarks"></span>
               </label>
             </div>
           </div>
           <div class="float-right">
            <div class="foodprice">20&#8377;</div>
          </div>
        </li>
      </ul>
    </div>
  
  </div>
  </div>
  
  </div>
  </div>
  <div class="modal-footer justify-content-center">
    <hr>
    <div class="over-hid ord-mod-total">
      <span class="float-left text-theme font-weight-bold f-24">Total</span>
      <span class="float-right text-theme f-24">&#8377;20</span>
    </div>
    <div class=" d-flex order-btn">
      <button type="button" class="btn btn-secondary">Order for today</button>
    <button type="button" class="btn ">Order for future</button>
    </div>
    
    {{-- <button type="button" class="btn btn-primary ord-btns"  data-clipboard-text="1">Order for future</button> --}}
  </div>
  </div>
  </div>
  </div>
  <button type="button" class="btn " data-toggle="modal" data-target="#exampleModal1">
    exampleModal
  </button> 
  
  <div class="modal fade my-mod-2" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
  
        <div class="modal-body">
          <div class="row align-items-center">
            <div class="col-lg-6 col-md-5">
               {{-- <div class="dispbloc-991" style="overflow: hidden;">
                <div class="close">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              </div> --}}
  
              <div class="modalimg">
                <img src="https://media-cdn.tripadvisor.com/media/photo-p/1a/33/74/f5/gourmet-burger.jpg" alt="">
              </div>
            </div>
            <div class="col-lg-6 col-md-7">
              <div class="dispnone-991" style="overflow: hidden;">
                <div class="close">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              </div>
              <div class="comment-cont">
                <div class="modalfooddet mt-2">
                  <div class="foodstat">
                    <ul class="prep-time">
                      <li class="font-weight-bold">
                        <h3 class="float-left font-weight-bold">Veg Burger</h3>
                        <h3 class="float-right text-theme font-weight-bold">&#8377;30</h3>
                      </li>
                      <li>
                        <span class="float-left">Preperation time</span>
                        <span class="float-right">2hr</span>
                      </li>
                    </ul>
                    <div class="details">
                      <h4>Details</h4>
                      <p class="text-muted muted-font">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, nulla.</p>
                    </div>
                  </div>
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
                              <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
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
                              <div class="viewreplies text-muted mt-1 mb-2">
                                --------<a href="#" class="text-muted ">View reply(2)</a>
                              </div>
                            </div>
                            <div class="float-right">
                              {{-- <i class="fa fa-heart like"></i> --}}
                              <i class="fa fa-heart-o like"></i>{{-- unlike --}}
                            </div>
                          </div>
                          <div class="cust-reply d-flex">
                            <div class="customer-reply-img float-left mr-3">
                              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&usqp=CAU" alt="">
                            </div>
                            <div class="float-left">
                              <h4 class="font-weight-bold">lilly</h4>
                              <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
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
                              <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
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
                              <div class="viewreplies text-muted mt-1 mb-2">
                                --------<a href="#" class="text-muted ">View reply(2)</a>
                              </div>
                            </div>
                            <div class="float-right">
                              {{-- <i class="fa fa-heart like"></i> --}}
                              <i class="fa fa-heart-o like"></i>{{-- unlike --}}
                            </div>
                          </div>
                          <div class="cust-reply d-flex">
                            <div class="customer-reply-img float-left mr-3">
                              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&usqp=CAU" alt="">
                            </div>
                            <div class="float-left">
                              <h4 class="font-weight-bold">lilly</h4>
                              <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
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
                </div>
                <div class="chefdetails chefdetail_s overflow-hidden mt-3">
                  <div class="mb-4">
                    <ul>
                      <li><a href="#"><span class="fa fa-heart-o "></span></a></li>
                      <li><a href="#"><img src="assets/front/img/comment.svg"></a></li>
                      <li><a href="#"><img src="assets/front/img/share.svg"></a></li>
                    </ul>
                    <p>255 LIKES</p>
                  </div>
                  <div class="post-comment">
                    <form action="">
                      <div class="d-flex justify-content-evently">
                        <textarea name="" id="" placeholder="Add a comment" style="width:100%;border: none;"></textarea>
                        <a href="#" style="">Post</a>
                      </div>
                      
    
                    </form>
                  </div>
    
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid area-asw">
 <!--  <section class="chefsfood-search"> -->
   <div class="searchbyfood">
    <div class="row justify-content-center text-center"><!--food-lists-->
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
        </div>  
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://img.webmd.com/dtmcms/live/webmd/consumer_assets/site_images/article_thumbnails/quizzes/fast_food_smarts_rmq/650x350_fast_food_smarts_rmq.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Burger</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://imagesvc.meredithcorp.io/v3/mm/image?q=85&c=sc&poi=face&url=https%3A%2F%2Fimg1.cookinglight.timeinc.net%2Fsites%2Fdefault%2Ffiles%2Fstyles%2F4_3_horizontal_-_1200x900%2Fpublic%2F1598640421%2FGeneral%20Tsos%20Chicken_Greg%20Dupree.jpg%3Fitok%3DaoZBvutd" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.clubmahindra.com/blog/media/section_images/traditiona-4587f0ec65deaf1.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Idli</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://miro.medium.com/max/1200/1*M1k4Pw_y8XgeOrvUL8NboQ.jpeg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Dosa</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.clubmahindra.com/blog/media/section_images/traditiona-4587f0ec65deaf1.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.clubmahindra.com/blog/media/section_images/traditiona-4587f0ec65deaf1.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://img.webmd.com/dtmcms/live/webmd/consumer_assets/site_images/article_thumbnails/quizzes/fast_food_smarts_rmq/650x350_fast_food_smarts_rmq.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 ">
        <div class="food-lists-img">
          <img src="https://img.webmd.com/dtmcms/live/webmd/consumer_assets/site_images/article_thumbnails/quizzes/fast_food_smarts_rmq/650x350_fast_food_smarts_rmq.jpg" alt="">
        </div>
        <div class="food-list-det">
          <h2 class=" text-black">Biriyani</h2>
          <h3 class="text-muted elipsis-text my-3 fooddesc">Lorem ipsum dolor sit amet, consectetur.</h3>
          <h3 class="text-theme">&#8377;10</h3>
          <p class="text-muted">Customizable</p>
          <a href="#" class="btn btn-theme-small btn-small addbutton">Add</a>
        </div>
      </div>
    </div>

  </div>




</div>
</div>

@endsection

<script>
  $('.ord-btn').tooltip({
    trigger: 'click',
    placement: 'bottom'
  });

  function setTooltip() {
    $('.ord-btn').tooltip('hide')
    .attr('data-original-title', "hello");
    .tooltip('show');
  }

  function hideTooltip() {
    setTimeout(function() {
      $('.ord-btn').tooltip('hide');
    }, 1000);
  }
  var clipboard = new Clipboard('.ord-btn');

  clipboard.on('success', function(e) {
    setTooltip('Copied!');
    hideTooltip();
  });

  clipboard.on('error', function(e) {
    setTooltip('Failed!');
    hideTooltip();
  });

</script>