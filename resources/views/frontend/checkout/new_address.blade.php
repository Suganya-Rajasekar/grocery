<div class="address-asw-checkout">
    <div class="addr-box">
        <div class="close-asw">
            <h3 class="font-opensans"><span>X</span> Save Delivery Address</h3>
        </div>
        <div class="map" id="myaddrMap">
            <!-- <img src="https://www.andysowards.com/blog/assets/google-maps-how-to-plan-awesome-vacation.jpg" alt="map"> -->
        </div>
        <div class="form">
            <form role="form" action="" method="post" id="address_form">
                <input type="text" placeholder="Address" class="form-control font-montserrat" id="location" name="location" value="Simmakkal, Madurai Main, Madurai, Tamil Nadu, India" required>
                <div class="alert_fn"></div>
                {{-- 
                <input type="text" placeholder=" Pincode" class="form-control font-montserrat" name="building" id="pincode" required>
                <input type="text" placeholder="Landmark" class="form-control font-montserrat" name="landmark" id="landmark" required> --}}
                <input type="text" placeholder=" Pincode" class="form-control font-montserrat" name="building" id="pincode" required>
                <input type="text" placeholder="Flat, House no, Building, Company, Apartment" class="form-control font-montserrat" name="Apartment" id="Apartment" required>
                <input type="text" placeholder="Area, Colony, Street, Sector, Village" class="form-control font-montserrat" name="landmark" id="landmark" required>
                <input type="text" placeholder="Landmark" class="form-control font-montserrat" name="landmark" id="landmark" required>
                <input type="text" placeholder="Town / city" class="form-control font-montserrat" name="city" id="city" required>
                <input type="text" placeholder="State / Province / Region" class="form-control font-montserrat" name="state" id="state" required>
                <div class="d-flex btns">
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
                    <div class="form-check">
                        <input class="form-check-input font-montserrat" type="radio" value="other" name="address_type" id="flexRadioDefault3">
                        <label class="form-check-label font-montserrat" for="flexRadioDefault3">
                            Others
                        </label>
                    </div>
                </div>
                <input type="hidden" name="a_lat" id="a_lat" value="9.9238447">
                <input type="hidden" name="a_lang" id="a_lang" value="78.1221683">
                <input type="hidden" name="a_addr" id="a_addr" value="146-147, Vakkil New St, Simmakkal, Madurai Main, Madurai, Tamil Nadu 625001, India">
                <button type="submit" class="btn font-montserrat" >save address & proceed</button>
            </form>
        </div>
    </div>
</div>