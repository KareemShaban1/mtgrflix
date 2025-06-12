

<div class="col-lg-4">
    <div class="card shadow-none mb-3 mb-lg-0 border d-none d-lg-block">
        <div class="card-body">
            <div class="list-group list-group-flush">
                <a href="{{ route('my-notification') }}"
                   class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-notification') ? 'active' : 'bg-transparent' }}">
                    {{ __('site.notifications') }}
                    <i class='bx bx-bell fs-5'></i>
                </a>

                <a href="{{ route('my-orders') }}"
                   class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-orders') ? 'active' : 'bg-transparent' }}">
                    {{ __('site.orders') }}
                    <i class='bx bx-cart fs-5'></i>
                </a>

                <a href="{{ route('my-profile') }}"
                   class="list-group-item d-flex justify-content-between align-items-center {{ request()->routeIs('my-profile') ? 'active' : 'bg-transparent' }}">
                    {{ __('site.my_account') }}
                    <i class='bx bx-user fs-5'></i>
                </a>

                <a href="{{ route('logout') }}"
                   class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-danger">
                    {{ __('site.logout') }}
                    <i class='bx bx-log-out fs-5'></i>
                </a>
            </div>
        </div>
    </div>
</div>
