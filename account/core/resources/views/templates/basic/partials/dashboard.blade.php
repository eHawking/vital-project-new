<style>
.button-container {
  display: flex;
  justify-content: center;
  align-items: center;
  
}
.dashboard-user::before {
    display: none;
}
.name {
  color: #ffffff; /* Adjust color as needed */
  text-shadow: 2px 2px 4px rgba(0,0,0,0.5); /* 3D backdrop effect */
}

 @keyframes ring {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
        75% { transform: rotate(5deg); }
        100% { transform: rotate(0deg); }
    }

    /* Apply animation to the bell icon */
    .bell-icon {
        display: inline-block;
		color: red; /* Change the color here */
        animation: ring 1s ease-in-out infinite; /* Adjust the duration as needed */
    }
		@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.pulse-animation {
  animation: pulse 1s infinite;
}

</style>
<section class="user-dashboard" style="top: 15px; padding-bottom: 50px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="dashboard-sidebar" style="top: 15px;">
					
                    <div class="close-dashboard d-lg-none">
                        <i class="las la-times" style="color: #ffffff;"></i>
                    </div>
					
					 
                    <div class="dashboard-user" style="background-color: #7e2afc80;">
						
						@if(auth()->user()->plan_id == 1)
							<div>
<i id="checkIcon" class="fas fa-check-circle" style="font-size: 30px; top: 35%; left: 50%; transform: translate(-50%, -50%); background-color: white; color: blue; padding: 0px; position: absolute; border-radius: 50%; z-index: 999;"></i>



 <div id="popup" style="display: none; top: 44%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 3px; border: 1px solid blue; border-radius: 3px; z-index: 999; position: absolute; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); color: black; text-align: center; width: 100%;">
    Verified Paid <span style="text-transform: uppercase;">{{ auth()->user()->dsp_username }}</span>
</div>
</div>
						
                        <div class="user-thumb" style="border: 6px solid #2ecc71 !important;">
							<img id="output" src="/storage/app/public/profile/{{ auth()->user()->image }}" alt="dashboard">
                        </div>
							@else
							<div class="user-thumb">
                            <img id="output" src="/storage/app/public/profile/{{ auth()->user()->image }}" alt="dashboard">
                        </div>
							@endif
							
                        <div class="user-content">
                            <span style="color: #ffffff;">@lang('WELCOME')</span>
                            <h5 class="name">{{ auth()->user()->fullname }}</h5>
							
								<span style="color: #ffffff;">@lang('ACCOUNT ID')</span>
							<div class="name" style="
    color: #fff;
	background-color: #fff;
    padding: 3px;
    border-radius: 3px;
    width: 100%;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5); /* Add backdrop shadow */
">
   <h5 style="
    font-size: 20px;
    font-weight: bold;
    color: #000000;
    border-radius: 3px;
    text-transform: uppercase;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Adjusted text shadow for 3D effect */
    transform: skew(-15deg, 0);
    display: inline; /* Ensures inline display */
    margin: 0; /* Remove default margin */
    padding: 0; /* Remove default padding */
">
    {{ auth()->user()->username }}
</h5>
 

</div>
						
                        </div>
						
                           
                           <a class="custom--btn" href="https://dewdropskin.com">
							   <i class="fas fa-store" style="color: white; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5); box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);"></i> <span style="color: white; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5);">@lang('Back to Marketplace')</span></a>
                      
                    </div>
                    <ul class="user-dashboard-tab">
                        <li>
                            <a class="{{menuActive('user.home')}}" href="{{route('user.home')}}"> <i class="bi bi-speedometer2"></i> @lang('Dasboard')</a>
                        </li>
                       <li class="pulse-animation">
  <a class="{{menuActive('user.notifications')}}" href="{{route('user.notifications')}}">
    <i class="bi bi-bell bell-icon"></i> @lang('LIVE Notifications')
  </a>
</li>
                        <li>
                            <a class="{{menuActive('user.my.ref')}}" href="{{ route('user.my.ref') }}">  <i class="bi bi-people"></i> @lang('My Referrals')</a>
                        </li>
						<li>
    <a class="{{menuActive('user.my.summery')}}" href="{{ route('user.my.summery') }}"> <i class="bi bi-bar-chart-line"></i> @lang('My Summery')</a>
</li>
                        <li>
                            <a class="{{menuActive('user.my.tree')}}" href="{{ route('user.my.tree') }}"> <i class="bi bi-diagram-3"></i> @lang('My Tree')</a>
                        </li>
						<li>
    <a class="{{menuActive('user.shop-franchise')}}" href="{{ route('user.shops-franchises') }}">
        <i class="bi bi-shop"></i> @lang('Shops & Franchises')
    </a>
</li>
                       <li>
        <a href="{{ route('user.dsp.vouchers') }}" class="{{menuActive('user.dsp.vouchers')}}"><i class="bi bi-gift"></i>
            @lang('DSP Vouchers')   

        </a>
    </li>
                       
                <li>
    <a href="{{ route('user.rewards') }}" class="{{ menuActive('user.rewards') }}">
        <i class="bi bi-award"></i> @lang('Ranks & Rewards')
    </a>
</li>



                        
                        <li>
                            <a href="{{ route('user.withdraw.history') }}" class="{{menuActive('user.withdraw*')}}"><i class="bi bi-cash-coin"></i> @lang('Withdraw History')
                            </a>
                        </li>
                       
                        <li>
                            <a href="{{ route('user.transactions') }}" class="{{menuActive('user.transactions')}}"><i class="bi bi-file-earmark-text"></i>
                                @lang('Transactions History')
                            </a>
                        </li>
                      
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">

                <div class="user-toggler-wrapper d-flex d-lg-none">
                    <h4 class="title">{{ __($pageTitle) }}</h4>
                    <div class="user-toggler">
                        <i class="las la-sliders-h"></i>
                    </div>
                </div>


                @yield('content')
            </div>
        </div>
    </div>
</section>
<script>
  // JavaScript code to show popup on hover
  document.getElementById("checkIcon").addEventListener("mouseenter", function() {
    document.getElementById("popup").style.display = "block";
  });

  document.getElementById("checkIcon").addEventListener("mouseleave", function() {
    document.getElementById("popup").style.display = "none";
  });
</script>