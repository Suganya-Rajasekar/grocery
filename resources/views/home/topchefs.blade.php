@extends('main.app')
@section('content')
<section class="topsec">
    <div class="container-fluid">      
    <section class="filterby my-5">
        <ul class="d-flex filter-checkbox">
            <li>
                <div class=" custom-checkbox">
                    <label class="container-check" for="">Popular near you
                    <input type="checkbox" class="custom-control-input">
                        <span class="checkmarks"></span>
                     </label>
                </div> 
            </li>
            <li>
                <div class=" custom-checkbox">
                    <label class="container-check" for="">Top rated chefs
                    <input type="checkbox" class="custom-control-input" checked>
                        <span class="checkmarks"></span>
                     </label>
                </div> 
            </li>
            <li>
                <div class=" custom-checkbox">
                    <label class="container-check" for="">Sponsered chefs
                    
                    <input type="checkbox" class="custom-control-input" checked>
                        <span class="checkmarks"></span>
                     </label>
                </div> 
            </li>
            <li>
                <div class=" custom-checkbox">
                    <label class="container-check" for="">Near by chefs
                    
                    <input type="checkbox" class="custom-control-input" checked>
                        <span class="checkmarks"></span>
                     </label>
                </div> 
            </li>
        </ul>
    </section>
</div>
</section>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="searchbychef">
            
                <div class="chef-lists mb-5">
                    <div class="chefdetails my-3">
                        <div class="d-flex">
                                <div class=" ">
                                    <div class="chefimg">
                                       <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                    </div> 
                                </div>
                                <div class="w-100 ml-3">
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="chefname">
                                             <h2 class="text-black font-weight-bold">Lily</h2>
                                             <h4 class="text-muted">South indian</h4>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="tag-ribbon">
                                            <span class="fa fa-bookmark"></span>
                                          </div>
                                       </div>
                                   </div>
                                   <div class="o-hid">
                                       <div class="float-left">
                                          <div class="sqr-star mt-3">
                                            <div class=" star-rating ">
                                        
                                        <div class="overflow-hidden">
                                            <div class="float-left">
                                              <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                <label for="condition_1" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                            </div>
                                         <span class="star-points text-black">4.5</span>
                                            <div class="float-right">(</div>
                                        </div>
                                    </div>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="pricefornos">
                                            <h4>$20 for  two</h4>
                                          </div>
                                       </div>
                                   </div>
                            </div>
                        </div>
                    </div>
                    <div class="chefsfood">
                        <div class="row mt-5">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                <div class="chefsfoodlists">
                                    <div class="foodimg">
                                       <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                    </div>
                                    <div class="fooddesc">
                                        <h2 class="food-name text-black">
                                            vadai
                                        </h2>
                                        <p class="1-elipsis-text"></p>
                                    </div>
                                    <div class="foodprice">
                                        <h2 class="text-black">$10</h2>
                                    </div>
                                    </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                <div class="chefsfoodlists">
                                    <div class="foodimg">
                                       <img src="https://images2.minutemediacdn.com/image/upload/c_crop,h_1126,w_2000,x_0,y_181/f_auto,q_auto,w_1100/v1554932288/shape/mentalfloss/12531-istock-637790866.jpg" alt="">
                                    </div>
                                    <div class="fooddesc">
                                        <h2 class="food-name text-black">
                                            vadai
                                        </h2>
                                        <p class="1-elipsis-text"></p>
                                    </div>
                                    <div class="foodprice">
                                        <h2 class="text-black">$10</h2>
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
</section>
@endsection