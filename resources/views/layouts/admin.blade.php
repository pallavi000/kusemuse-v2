
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ecommerce </title>
  <link rel="stylesheet" href="{{ asset('admin') }}/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ asset('admin') }}/plugins/fontawesome-free/css/all.min.css">

  

  <link rel="stylesheet" href="{{ asset('admin') }}/css/admin.css">

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


  @yield('style')
</head>
<body class="hold-transition admin_sidebar-mini">


<!-- Admin CSS -->
@if(auth()->user()->is_admin==1)
<div class="admin_sidebar admin_close">
    <div class="logo-details">
      <i class='togglebars fa fa-bars' ></i>
      <span class="logo_name">KUSEMUSE</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="{{ route('admin.dashboard') }}">
          <i class='fa fa-th-large' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        </ul>
      </li>
      <li>
    
    <a href="{{ route('users.index') }}">
      <i class='fa fa-user' ></i>
      <span class="link_name">Admins/Sellers</span>
    </a>     
  <ul class="sub-menu blank">
    <li><a class="link_name" href="{{ route('users.index') }}">Admins/Sellers</a></li>
  </ul>
</li>
      <li>
        <div class="iocn-link">
          <a href="{{ route('admin.product.index') }}">
            <i class='fa fa-product-hunt' ></i>
            <span class="link_name">Products</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="{{ route('admin.product.index') }}">Products</a></li>
          <li><a href="{{ route('admin.coupon.index') }}">Coupon</a></li>
          <li><a href="{{ route('size.index') }}">Size</a></li>
          <li><a href="{{ route('color.index') }}">Color</a></li>

        </ul>
      </li>
      <li>
      <div class="iocn-link">
          <a href="{{ route('admin.boutique.index') }}">
            <i class='fa fa-modx' ></i>
            <span class="link_name">Boutique</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="{{ route('admin.boutique.index') }}">Boutique</a></li>
          <li><a href="{{ route('admin.boutique.create') }}">Add Boutique</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="{{ route('category.index') }}">
            <i class='fa fa-chrome' ></i>
            <span class="link_name">Category</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="{{ route('category.index') }}">Category</a></li>
          <li><a href="{{ route('category.create') }}">Add Category</a></li>
        </ul>
      </li>
     

      <li>
        <div class="iocn-link">
          <a href="{{ route('banner.index') }}">
            <i class='fa fa-audio-description' ></i>
            <span class="link_name">Banners</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="{{ route('banner.index') }}">Page Banner</a></li>
          <li><a href="{{ route('categorybanner.index') }}">Category Banner</a></li>
          <li><a href="{{ route('featureblock.index') }}">Feature Brand Banner</a></li>
        </ul>
      </li>

      
      <li>
          <a href="{{ route('brand.index') }}">
            <i class='fa fa-bold' ></i>
            <span class="link_name">Brands</span>
          </a>
      
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('brand.index') }}">Brands</a></li>

        </ul>
      </li>

      <li>
    
          <a href="{{ route('designer.index') }}">
            <i class='fa fa-yelp' ></i>
            <span class="link_name">Designers</span>
          </a>     
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('designer.index') }}">Designers</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="{{ route('shipping.index') }}">
            <i class='fa fa-truck' ></i>
            <span class="link_name">Shippings</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="{{ route('shipping.index') }}">Shippings</a></li>
          <li><a href="{{ route('shipping.create') }}">Add Shipping</a></li>
          <li><a href="{{ route('country.index') }}">Add Country</a></li>
          <li><a href="{{ route('city.index') }}">Add City</a></li>
        </ul>
      </li>

      <li>
      <a href="{{ route('page.index') }}">
        <i class='fa fa-yelp' ></i>
        <span class="link_name">Pages</span>
      </a>     
    <ul class="sub-menu blank">
      <li><a class="link_name" href="{{ route('page.index') }}">Pages</a></li>
    </ul>
  </li>
  <li>


      <li>
        <div class="iocn-link">
        <a href="{{ route('admin.order.index') }}">
          <i class='fa fa-shopping-cart' ></i>
            <span class="link_name">Orders</span>
          </a>
          <i class='fa fa-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
        <li><a class="link_name" href="{{ route('admin.order.index') }}">Orders</a></li>
        <li><a href="{{ route('admin.withdraw.index') }}">Withdraw</a></li>
        <li><a href="{{ route('admin.customer.index') }}">Customers</a></li>
        <li><a href="{{ route('review.index') }}">Reviews</a></li>
        </ul>
      </li>



     
      <li>
    <div class="profile-details">
      <div class="profile-content">

      </div>
      <div class="name-job">
        <div class="profile_name">{{auth()->user()->name}}</div>
        <div class="job">{{auth()->user()->role}}</div>
      </div>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
<i class='fa fa-sign-out' ></i></a>
    </div>
  </li>
</ul>
</div>




@else

<div class="admin_sidebar admin_close">
    <div class="logo-details">
      <i class='togglebars fa fa-bars' ></i>
      <span class="logo_name">KUSEMUSE</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="{{ route('seller.dashboard') }}">
          <i class='fa fa-th-large' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('seller.dashboard') }}">Dashboard</a></li>
        </ul>
      </li>


      <li>
        @if(auth()->user()->role=="brand_seller")
        <a href="{{ route('product.index') }}">
          
          <i class='fa fa-product-hunt' ></i>
          <span class="link_name">Products</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('product.index') }}">Products</a></li>
        </ul>
        
        @else
        <a href="{{ route('boutique.index') }}">
          
          <i class='fa fa-product-hunt' ></i>
          <span class="link_name">Boutique</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('boutique.index') }}">Boutique</a></li>
        </ul>
        @endif
      </li>

      <li>
        <a href="{{ route('order.index') }}">
          <i class='fa fa-shopping-cart' ></i>
          <span class="link_name">Orders</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('order.index') }}">Orders</a></li>
        </ul>
      </li>

      <li>
        <a href="{{ route('order.index') }}">
          <i class='fa fa-exchange' ></i>
          <span class="link_name">Transaction</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('order.index') }}">Transaction</a></li>
        </ul>
      </li>

      <li>
        <a href="{{ route('withdraw.index') }}">
          <i class='fa fa-money' ></i>
          <span class="link_name">Withdraw</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('withdraw.index') }}">Withdraw</a></li>
        </ul>
      </li>
<!-- 
      <li>
        <a href="{{ route('coupon.index') }}">
          <i class='fa fa-th-large' ></i>
          <span class="link_name">Coupons</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('coupon.index') }}">Coupons</a></li>
        </ul>
      </li> -->

      <!-- <li>
        <a href="{{ route('customer.index') }}">
          <i class='fa fa-user' ></i>
          <span class="link_name">Customers</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="{{ route('customer.index') }}">Customers</a></li>
        </ul>
      </li> -->

      <li>
    <div class="profile-details">
      <div class="profile-content">

      </div>
      <div class="name-job">
        <div class="profile_name">{{auth()->user()->name}}</div>
        <div class="job">{{auth()->user()->role}}</div>
      </div>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
<i class='fa fa-sign-out' ></i></a>
    </div>
  </li>
</ul>
</div>


@endif





  <!-- Content Wrapper. Contains page content -->
  <div class="home-section py-5">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->



  


<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>


<script src="{{ asset('admin') }}/js/bs-custom-file-input.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    


@yield('script')
<script>
  @if(Session::has('success'))
  toastr.success("{{ Session::get('success') }}");
  @endif
  $(document).ready(function () {
    bsCustomFileInput.init()
  })



  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let admin_sidebar = document.querySelector(".admin_sidebar");
  let admin_sidebarBtn = document.querySelector(".togglebars");
  admin_sidebarBtn.addEventListener("click", ()=>{
  	if(admin_sidebarBtn.classList.contains("fa-align-right")) {
  		  	document.querySelector(".togglebars").classList.replace('fa-align-right','fa-bars')
  	} else {
  		document.querySelector(".togglebars").classList.replace('fa-bars','fa-align-right')
  	}
    admin_sidebar.classList.toggle("admin_close");
  });
 

</script>
</body>
</html>
