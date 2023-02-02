<style type="text/css">
    #wallet-container {
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }
    #wallet-img {
        width: 120px;
        height: 120px;
    }
    .wallet_amt {      
        display: inline;
        font-weight: bold;
        color: #f55a60;
        font-size: 32px;
    }
</style>
<div class="settings-content-area">
    <h4 class="font-montserrat">Wallet </h4> 
    <div class="row p-2 justify-content-md-start justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-6 pr-3">
            <div id="wallet-container" >
                <h4 class="font-montserrat pt-3 pl-3">Knosh Wallet</h4>
            <div class="d-flex justify-content-around align-items-center py-3">  
                <img src="{{ url('/assets/front/img/walletinside.svg') }}" id="wallet-img">
                <p class="font-montserrat wallet_amt mb-0">₹{{ $wallet }}</p>
            </div>

            </div>
        </div> 
        {{-- <div class="col-12 col-sm-8 col-md-6 col-lg-6 pl-3 mt-4 mt-md-0" >
            <div id="wallet-container" >
            <h4 class="font-montserrat pt-3 pl-3">Loyalty points earned</h4>
            <div class="d-flex justify-content-around align-items-center py-3">  
               <div><img src="{{ url('/assets/front/img/loyaltypoints.svg') }}" id="wallet-img"></div>
               <div><p class="font-montserrat wallet_amt mb-0">₹{{ $wallet->wallet_amount }}</p></div>
            </div>
            </div>
        </div> --}}
    </div>                             
</div>
<div class="container mt-3">
        <table class="table table-striped">
            <thead>
              <tr>
                <th>s.no</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
                @foreach($wallet_history as $key => $value) 
                <tr>
                    <td>{{ $key + 1}}</td>
                    @if($value->type == 'credit')
                    <td class="font-monstserrat text-success">+ {{ $value->amount }}</td>
                    @else 
                    <td class="font-monstserrat text-danger">- {{ $value->amount }}</td>
                    @endif
                    <td>{{ $value->notes }}</td>
                    <td>{{ $value->history_date }}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
  </div>