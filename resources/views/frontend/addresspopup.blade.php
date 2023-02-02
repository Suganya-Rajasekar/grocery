<div class="address-asw-checkout address-blj">
    <div class="addr-box">
        <div class="close-asw">
            <h3 class="font-opensans"><span>x</span> Save Delivery Address</h3>
        </div>
        <div class="map" id="myaddrMap">
            <!-- <img src="https://www.andysowards.com/blog/assets/google-maps-how-to-plan-awesome-vacation.jpg" alt="map"> -->
        </div>
        <div class="form">
            <form role="form" action="" method="post" id="address_form">
                <div class="form-bl">
                    <label>Complete Address</label>
                    <div class="bg-input">
                        <input type="text" class="form-control font-montserrat " id="location" name="location" value="" placeholder="" required>
                        <p class="para-bl error-toast"></p>
                    </div>
                </div>
                <div>
                    <label>Flat, House no</label>
                    <input type="text" class="form-control font-montserrat" name="building" id="building" required>
                </div>
                <div>
                    <label>Area, Colony</label>
                    <input type="text" class="form-control font-montserrat" name="area" id="area" required>
                </div>
                <div>
                    <label>Landmark</label>
                    <input type="text" class="form-control font-montserrat" name="landmark" id="landmark">
                </div>
                <div>
                    <label>Town / city</label>
                    <input type="text" class="form-control font-montserrat" name="city" id="city" required>
                </div>
                <div>
                    <label>State</label>
                    <input type="text" class="form-control font-montserrat" name="state" id="state" required>
                </div>
                <div>
                    <label>Pincode</label>
                    <input type="text" class="form-control font-montserrat" name="pin_code" id="pin_code" required>
                </div>

                <div class="d-flex btns address_types">
                    <div class="form-check">
                        <input class="font-montserrat form-check-input" type="radio" value="home" name="address_type" id="flexRadioDefault1" checked>
                        <label class="form-check-label font-montserrat" for="flexRadioDefault1">
                            Home
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input font-montserrat" type="radio" value="office" name="address_type" id="flexRadioDefault2" >
                        <label class="form-check-label font-montserrat" for="flexRadioDefault2">
                            Office
                        </label>
                    </div>
                    <div class="form-check" id="address_type_others">
                        <input class="form-check-input font-montserrat" type="radio" name="address_type" value="other" id="flexRadioDefault3">
                        <label class="form-check-label font-montserrat" for="flexRadioDefault3">
                            Others
                        </label>
                    </div>
                </div>
                <div class="d-flex btns" id="address_type_text" style="display:none!important;">
                    <input class="form-control font-montserrat" type="text" name="address_type_text" id="address_type">
                </div>
                <div style="text-align: end;display: none;" id="cancel-btn">
                    <a class="btn btn-primary">cancel</a>
                </div>
                <input type="hidden" name="a_lat" id="a_lat" value="9.9238447">
                <input type="hidden" name="a_lang" id="a_lang" value="78.1221683">
                <input type="hidden" name="a_addr" id="a_addr" value="146-147, Vakkil New St, Simmakkal, Madurai Main, Madurai, Tamil Nadu 625001, India">
                <input type="hidden" name="address_id" id="address_id" value="">
                <button type="submit" class="btn font-montserrat" id="saveadd">Save Address & Proceed</button>
            </form>
        </div>
    </div>
</div>
