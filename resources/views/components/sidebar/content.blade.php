<x-perfect-scrollbar
    as="nav"
    aria-label="main"
    class="flex flex-col flex-1 gap-4 px-3"
>


        <x-sidebar.link
            title='Dashboard'
            href="{{ route('dashboard') }}"
            :isActive="request()->routeIs('dashboard')"
        >

        <x-slot name="icon">
            <x-heroicon-o-home  class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        </x-sidebar.link>

        <x-sidebar.link
            title="Books"
            href="{{ route('bookList') }}"
            :isActive="request()->routeIs('bookList')"
            >
            <x-slot name="icon">
                <x-heroicon-o-bookmark class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

        </x-sidebar.link>


        <x-sidebar.link
        title="History"
        href="{{ route('history') }}"
        :isActive="request()->routeIs('history')"
    >
        <x-slot name="icon">
            <x-heroicon-o-trash class="flex-shrink-0 w-6 h-6" aria-hidden="true" />

            <!-- Conditionally display the badge for history -->
            @php
            $loggedInUserId = Auth::id();
            $historyCount = App\Models\UserNotification::where('user_id', $loggedInUserId)->count();
            @endphp

            @if ($historyCount > 0)
            <span class="bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-30 right-1">
                {{ $historyCount }}
            </span>
            @endif
        </x-slot>
    </x-sidebar.link>






    @php
    $loggedInUserId = Auth::id();
    $acceptedRequestsCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)->count();
    @endphp

    <x-sidebar.link
        title="Notification"
        href="{{ route('notifications') }}"
        :isActive="request()->routeIs('notifications')"
    >
        <x-slot name="icon">
            <x-heroicon-o-bell class="flex-shrink-0 w-6 h-6" aria-hidden="true" />

            <!-- Conditionally display the badge -->
            @if ($acceptedRequestsCount > 0)
            <span class="bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-50 right-1">
                {{ $acceptedRequestsCount }}
            </span>
            @endif
        </x-slot>
    </x-sidebar.link>







    @if (Auth::user()->is_admin)
        {{-- <x-sidebar.link
            title="Add Book"
            href="{{ route('book') }}"
            :isActive="request()->routeIs('book')"
        >
        </x-sidebar.link> --}}

        <x-sidebar.link
        title="Request"
        href="{{ route('requests') }}"
        :isActive="request()->routeIs('requests')"
    >
        <x-slot name="icon">
            <x-heroicon-o-bell class="flex-shrink-0 w-6 h-6" aria-hidden="true" />

            <!-- Conditionally display the badge for requests -->
            @php
            $totalRequests = DB::table('book_requests')->count();
            @endphp

            @if ($totalRequests > 0)
            <span class="bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-30 right-1">
                {{ $totalRequests }}
            </span>
            @endif
        </x-slot>
    </x-sidebar.link>



        <x-sidebar.link
            title="Transactions"
            href="{{ route('transactions') }}"
            :isActive="request()->routeIs('transactions')"
        >

        <x-slot name="icon">
            <x-heroicon-o-archive class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
        </x-sidebar.link>




        <x-sidebar.link
        title="Students"
        href="{{ route('student') }}"
        :isActive="request()->routeIs('student')"
    >

    <x-slot name="icon">
        <x-heroicon-o-users class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
    </x-slot>
    </x-sidebar.link>

    @endif
</x-perfect-scrollbar>
