<div id="menu-profile"
     class="menu menu-box-med menu-box-detached rounded-l"
     data-menu-height="120"
     data-menu-effect="menu-over">


    <div class="row text-center pt-4">
        <div class="col-4">
            <div class="item bg-theme">
                <a href="#" class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark"><i
                    class="fa fa-shopping-cart font-20"></i></a>
                <h5 class="font-12 mt-2"> طلباتي </h5>

            </div>
        </div>

        <div class="col-4">
            <div class="item bg-theme">
                <a href="#" data-menu="menu-orders"
                   class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark"><i
                        class="fa fa-shopping-cart font-20"></i></a>
                <h5 class="font-12 mt-2"> تتبع اخر طلب</h5>

            </div>
        </div>


        <div class="col-4">
            <div class="item bg-theme">
                <a href="{{ route('user_logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                   class="icon icon-l color-white border-yellow1-dark icon-border color-yellow1-dark">
                    <i class="fa fa-sign-out-alt font-20"></i>
                </a>
                <h5 class="font-12 mt-2">@lang('messages.logout')</h5>
                <form id="logout-form" action="{{ route('user_logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </div>


    </div>
</div>
