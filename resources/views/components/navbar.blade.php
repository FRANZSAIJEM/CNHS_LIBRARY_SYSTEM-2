<nav
    aria-label="secondary"
    x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white shadow-md dark:bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">

    </div>

    <div class="flex items-center gap-3">


        @php
        $loggedInUserId = Auth::id();

        // Check if the user has visited the notifications page
        $visitedNotificationsPage = session('visited_notifications_page', false);

        // Count accepted requests with fines greater than 0
        $acceptedRequestsWithFinesCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)
            ->where('fines', '>', 0.00)
            ->count();

        $acceptedRequestsCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)->count();

        // Count replies received by the logged-in user, excluding their own replies
        $repliesCount = App\Models\Reply::whereHas('comment', function ($query) use ($loggedInUserId) {
            $query->where('user_id', $loggedInUserId);
        })
        ->where('user_id', '!=', $loggedInUserId) // Exclude own replies
        ->count();

        // Count likes received by the logged-in user, excluding their own likes
        $reactsCount = App\Models\CommentLike::whereHas('comment', function ($query) use ($loggedInUserId) {
            $query->where('user_id', $loggedInUserId);
        })
        ->where('user_id', '!=', $loggedInUserId) // Exclude own likes
        ->count();

        $currentRouteIsNotifications = request()->routeIs('notifications');

        // Check if the user is on the notifications page and set the session
        if ($currentRouteIsNotifications) {
            session(['visited_notifications_page' => true]);
        }

        @endphp

    <?php
    $loggedInUserId = Auth::id(); // Get the logged-in user's ID
    $acceptedRequestCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)->count();
    ?>

        @if (!Auth::user()->is_admin)
        <div>
            @if ($acceptedRequestCount > 0)
                <a style="padding: 10px;" class="rounded-lg hover:bg-slate-100 duration-100"  href="{{ route('navbar') }}">
                    <b style="font-size: 20px; display: inline-block; transform-origin: center; animation: rotate 2s linear infinite;"><i class="fa-solid fa-clock"></i></b>
                </a>
            @else
                <span>
                    <b style="font-size: 20px; display: inline-block; opacity: 50%;"><i class="fa-solid fa-clock"></i></b>
                </span>
            @endif
        </div>
        @endif
    <style>
        @if ($acceptedRequestCount == 0)
            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(0deg);
                }
            }
        @else
            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
        @endif
    </style>




        <x-sidebar.link

            href="{{ route('notifications') }}"

        >
            <x-slot name="icon">
                <x-heroicon-o-bell class="flex-shrink-0 w-6 h-6" aria-hidden="true" />

                @php
                $totalNotifications = $visitedNotificationsPage ? 0 : ($acceptedRequestsWithFinesCount + $acceptedRequestsCount + $repliesCount + $reactsCount);
                @endphp
                <!-- Conditionally display the badge -->
                @if ($totalNotifications > 0)
                <span class="bg-red-500 w-2.5 h-2.5 rounded-full absolute ms-5 mb-5"></span>
                {{-- <span class="text-dark w-7 text-center rounded-full px-2 py-1 text-xs absolute ms-3 mb-3">
                    <b>{{ $totalNotifications }}</b>
                </span> --}}
                @endif

            </x-slot>
        </x-sidebar.link>

        

        <x-dropdown align="right" width="48" style="z-index: 0;">

            <x-slot name="trigger">
                <button
                    class="flex items-center p-2 text-sm font-medium text-gray-500 rounded-md transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none focus:ring focus:ring-purple-500 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark-eval-1 dark:text-gray-400 dark:hover:text-gray-200"
                >
                <img class="rounded-full" width="50px" height="50px" src="{{ asset(Auth::user()->image) }}" alt="">

                    <div class="ml-1">
                        <svg
                            class="w-4 h-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <!-- Profile -->
                <x-dropdown-link
                    :href="route('profile.edit')"
                >
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>

<!-- Mobile bottom bar -->
<div
    class="fixed inset-x-0 bottom-0 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white md:hidden dark:bg-dark-eval-1"
    :class="{
        'translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }"
>


    <a href="{{ route('dashboard') }}">
        <img src="{{ asset('logo.png') }}" alt="" aria-hidden="true" class="w-10 h-10">
        {{-- <x-application-logo aria-hidden="true" class="w-10 h-10" /> --}}

        <span class="sr-only">Dashboard</span>
    </a>

    <x-button
        type="button"
        icon-only
        variant="secondary"
        sr-text="Open main menu"
        x-on:click="isSidebarOpen = !isSidebarOpen"
    >
        <x-heroicon-o-menu
            x-show="!isSidebarOpen"
            aria-hidden="true"
            class="w-6 h-6"
        />

        <x-heroicon-o-x
            x-show="isSidebarOpen"
            aria-hidden="true"
            class="w-6 h-6"
        />
    </x-button>
</div>
