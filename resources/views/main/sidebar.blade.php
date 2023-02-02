

<section class="vendor_side_bard">

   <div class="clearb">
        <div class="image_sec text-center">
        <i class="fa fa-handshake-o" aria-hidden="true"></i>
        <h4>Vendor Demo!</h4>
        <p>Last Login: October 4, 2020 9:00 AM</p>
    </div>
   </div>
   <div>

 <ul class="none_767">
        <li class="{{ Request::is('vendor/dashboard*') ? 'active' : '' }}"><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo URL::to('/').'/'; ?>vendor/dashboard">Dashboard</a> </li>
        
        <li class="{{ Request::is('vendor/usersManage*') ? 'active' : '' }}"><i class="fa fa-bars" aria-hidden="true"></i><a href="<?php echo URL::to('/').'/'; ?>vendor/usersManage">Users </a></li>

        <li class="{{ Request::is('vendor/coupons*') ? 'active' : '' }}"><i class="fa fa-tag" aria-hidden="true"></i><a href="<?php echo URL::to('/').'/'; ?>vendor/coupons">Discounts & Coupons</a> </li>

        <li class="{{ Request::is('vendor/transaction-history*') ? 'active' : '' }}"><i class="fa fa-file-text" aria-hidden="true"></i><a href="<?php echo URL::to('/').'/'; ?>vendor/transaction-history">Transaction History </a></li>
        
        <li class="{{ Request::is('vendor/payment-tracking*') ? 'active' : '' }}"><i class="fa fa-bar-chart" aria-hidden="true"></i><a href="<?php echo URL::to('/').'/'; ?>vendor/payment-tracking">Payment Tracking</a> </li>
    </ul>
</div>


<div class="block_767">
     <nav class="navbar navbar-expand-md navbar-dark headnav" style="background: #1b1d22;">
  <!-- Brand -->
  <a class="navbar-brand" href="#"></a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler headtogg" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link fa fa-home" href="Dashboard">&nbsp Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link fa fa-bars" href="#">&nbsp Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link fa fa-tag" href="Coupons">&nbsp Discounts & Coupons</a>
      </li>
      <li class="nav-item">
        <a class="nav-link fa fa-file-text" href="#">&nbsp Transaction History</a>
      </li>
      <li class="nav-item">
        <a class="nav-link fa fa-bar-chart" href="Payment">&nbsp Payment Tracking</a>
      </li>
    </ul>
  </div>
</nav> 
</div>
    
</section>


