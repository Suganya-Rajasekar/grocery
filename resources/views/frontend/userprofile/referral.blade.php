     <style type="text/css">
         #ref_container {
            margin:0 auto;
            display: flex;
            justify-content: flex-start;
            margin-right: 100px;
            margin-left:50px;
            margin-top: 40px;
         }
         #ref_text {
            text-indent:30px;
            font-family: montserrat!important;
         }
         #ref_text_head {
            font-family: montserrat!important;
            font-weight: bold;
         }
         #ref_text_container {
            margin-left:50px;
            margin-top: 50px;
            width: 900px;
         }
         #Referral {
            cursor: pointer;
         }
     </style>
    <div class="settings-content-area">
       <h4 class="font-montserrat">Referral </h4> 
       <div id="ref_text_container">
           <h5 id="ref_text_head">Invite your friends to Knosh</h5>
           <p id="ref_text">{{ $referral->referral_info }}</p>
        </div>
        <div id="ref_container">
            <div>
                <h5 id="ref_head">copy your code</h5>
            </div>
            <div class="ml-2">
            <span id="Referral" data-refcode="{{ $referral->referral_code }}"><i class="fa fa-clone"></i> {{ $referral->referral_code }}</span>
            </div>
            <div>
               <div class="ml-4" style="width: 25px;">
                  <span class="share-btn"><img src="{{ asset('assets/front/img/share.svg') }}"></span>
               </div>
               <div class="share-option-asw" style="transform: translateY(25px) !important;">
                  <div class="share-option" style="display: none;">
                     <div class="options d-inline">
                        <div class="option-btn d-inline">
                           <a href="https://www.facebook.com/sharer.php?caption=Knosh&description={{ $referral->share_description }}"
                            target="_blank" class="fb-share">
                              <i class="fa fa-facebook"></i>
                           </a>
                        </div>
                     </div>
                     <div class="options d-inline">
                        <div class="option-btn d-inline">
                           <a href="mailto:?subject=Knosh&amp;body={{ $referral->share_description }}" class="email-share">
                              <i class="fa fa-envelope"></i>
                           </a>
                        </div>
                     </div>
                     <div class="options d-inline">
                        <div class="option-btn d-inline">
                        <a href="whatsapp://send?text={{ $referral->share_description }}"  data-action="share/whatsapp/share" target="_blank"> <i class="fa fa-whatsapp"></i> </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
        </div>
    </div>
