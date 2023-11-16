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

            // Check if the user has visited the history page
            $visitedHistoryPage = session('visited_history_page', false);

            // If the user is on the history page, mark it as visited
            if (request()->routeIs('history')) {
                session(['visited_history_page' => true]);
            }
            @endphp

            @if (!$visitedHistoryPage && $historyCount > 0)
            {{-- <span class="bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-30 right-1">
                {{ $historyCount }}
            </span> --}}
            @endif
        </x-slot>
    </x-sidebar.link>


    @if (!Auth::user()->is_admin)
    <x-sidebar.link
        title="Chat Staff"
        href="{{ route('startChatStud') }}"
        :isActive="request()->routeIs('startChatStud')"
        >
        <x-slot name="icon">
            <x-heroicon-o-chat class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

    </x-sidebar.link>
    @endif


    @php
    $loggedInUserId = Auth::id();

    // Check if the user has visited the notifications page
    $visitedNotificationsPage = session('visited_notifications_page', false);

    // Count accepted requests with fines greater than 0
    $acceptedRequestsWithFinesCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)
        ->where('fines', '>', 0.00)
        ->count();

    $acceptedRequestsCount = App\Models\AcceptedRequest::where('user_id', $loggedInUserId)->count();

    $repliesCount = App\Models\Reply::where('comment_id', $loggedInUserId)
        ->where('user_id', '!=', $loggedInUserId) // Exclude own replies
        ->count();

    $reactsCount = App\Models\CommentLike::where('comment_id', $loggedInUserId)
        ->where('user_id', '!=', $loggedInUserId) // Exclude own likes
        ->count();

    $currentRouteIsNotifications = request()->routeIs('notifications');

    // Check if the user is on the notifications page and set the session
    if ($currentRouteIsNotifications) {
        session(['visited_notifications_page' => true]);
    }

    @endphp




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

    // Check if the user has visited the requests page
    $visitedRequestsPage = session('visited_requests_page', false);

    // If the user is on the requests page, mark it as visited
    if (request()->routeIs('requests') && !$visitedRequestsPage) {
        session(['visited_requests_page' => true]);
    }
    @endphp

    @if (!$visitedRequestsPage && $totalRequests > 0)
    {{-- <span class="bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-30 right-1">
        {{ $totalRequests }}
    </span> --}}
    @endif
</x-slot>
</x-sidebar.link>



    @if (Auth::user()->is_admin)






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
