<header class="main-header-section">
    <div class="header-wrapper">
        <div class="header-left">
            <div class="sidebar-opner"><i class="fal fa-bars" aria-hidden="true"></i></div>
        </div>
        <div class="header-middle"></div>
        <div class="header-right">
            @if (auth()->user()->role == 'superadmin')
            <div class="notifications dropdown">
                <a href="#" class="dropdown-toggleer mt-1 me-3" data-bs-toggle="dropdown">
                    <i><img src="{{ asset('assets/images/icons/bel.svg') }}" alt=""></i>
                    <span class="bg-red">{{ auth()->user()->unreadNotifications ->count() }}</span>
                </a>
                <div class="dropdown-menu">
                    <div class="notification-header">
                        <p>You Have <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> new Notifications</p>
                        <a href="{{ route('notifications.mtReadAll') }}" class="text-red">Mark all Read</a>
                    </div>
                    <ul>
                        @foreach (auth()->user()->unreadNotifications  as $notification)
                        <li>
                            <a href="{{ route('notifications.mtView', $notification->id) }}">
                                <strong>{{ __($notification->data['message'] ?? '') }}</strong>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="notification-footer">
                        <a class="text-red" href="{{ route('notifications.index') }}">View all notifications</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="profile-info dropdown">
                <a href="#"  data-bs-toggle="dropdown">
                    <img src="{{ asset(Auth::user()->image ?? 'assets/images/profile/profile-img.png') }}" alt="Profile">
                </a>
                <ul class="dropdown-menu">
                    <li> <a href="{{ url('cache-clear') }}"> <i class="far fa-undo"></i> {{ __('Clear cache') }}</a></li>
                    <li> <a href="{{ route('user-profile.index') }}"> <i class="fal fa-user"></i> {{__('My Profile')}}</a></li>
                    <li>
                        <a href="javascript:void(0)" class="logoutButton">
                            <i class="far fa-sign-out"></i> {{ __('Logout') }}
                            <form action="{{ route('logout') }}" method="post" id="logoutForm">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
