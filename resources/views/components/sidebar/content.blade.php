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

            <i  class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-house"></i>
        </x-slot>

        </x-sidebar.link>




        <x-sidebar.link
            title="Books"
            href="{{ route('bookList') }}"
            :isActive="request()->routeIs('bookList')"
            >
            <x-slot name="icon">
                <i  class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-book"></i>
                {{-- <x-heroicon-o-bookmark class="flex-shrink-0 w-6 h-6" aria-hidden="true" /> --}}
            </x-slot>

        </x-sidebar.link>






      @if (!Auth::user()->is_assistant)
      <x-sidebar.link
      title="History"
      href="{{ route('history') }}"
      :isActive="request()->routeIs('history')"
  >
      <x-slot name="icon">

          <i  class="fa-solid fa-clock-rotate-left w-6 h-6 flex justify-center mb-2 text-lg"></i>
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
      @endif



    @if (!Auth::user()->is_admin && !Auth::user()->is_assistant)
    <x-sidebar.link
        title="Chat Staff"
        href="{{ route('startChatStud') }}"
        :isActive="request()->routeIs('startChatStud')"
    >
        <x-slot name="icon">
            <i class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-comment"></i>
            <!-- Check for new chat messages and display badge if needed -->
            @php
                // Get the timestamp of the last time the user checked for new chat messages
                $lastCheckedChatsTime = auth()->user()->last_checked_chats;

                // Query for the latest chat message from staff directed to the current user
                $latestStaffChat = DB::table('chats')
                    ->where('receiver_id', auth()->id()) // Use 'receiver_id' instead of 'recipient_id'
                    ->where('sender_id', '!=', auth()->id()) // Optional: Exclude messages sent by the current user
                    ->latest('created_at')
                    ->first();

                // If the user is on the chat page, update last_checked_chats and store the current timestamp
                if (request()->routeIs('startChatStud') && $latestStaffChat && $latestStaffChat->created_at > $lastCheckedChatsTime) {
                    auth()->user()->update(['last_checked_chats' => now()]);
                }
            @endphp
            @if ($latestStaffChat && auth()->user()->last_checked_chats < $latestStaffChat->created_at)
                <span class="bg-red-500 w-2.5 h-2.5 rounded-full absolute ms-5 mb-5"></span>
            @endif
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
        <i class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-code-pull-request"></i>
        <!-- Check for new requests and display badge if needed -->
        @php
            // Get the total number of requests
            $totalRequests = DB::table('book_requests')->count();

            // Get the timestamp of the last time the user checked for new data
            $lastCheckedTime = auth()->user()->last_checked_requests;

            // Get the timestamp of the latest data (you might need to adjust this based on your data)
            $latestDataTime = DB::table('book_requests')->max('created_at');

            // If the user is on the requests page and there are new requests,
            // mark it as visited and show the badge
            if (request()->routeIs('requests') && $latestDataTime > $lastCheckedTime) {
                auth()->user()->update(['last_checked_requests' => now()]);
            }
        @endphp

       @if (Auth::user()->is_admin)
        @if ($totalRequests > 0 && auth()->user()->last_checked_requests < $latestDataTime)
        <span class="bg-red-500 w-2.5 h-2.5 rounded-full absolute ms-5 mb-5"></span>

            @endif
       @endif
    </x-slot>
</x-sidebar.link>






{{-- bg-slate-600 text-white w-7 text-center rounded-full px-2 py-1 text-xs absolute top-30 right-1 --}}

    @if (Auth::user()->is_admin || Auth::user()->is_assistant)
        <x-sidebar.link
            title="Borrowed Books"
            href="{{ route('transactions') }}"
            :isActive="request()->routeIs('transactions')"
        >

        <x-slot name="icon">

            <i  class="fas w-6 h-6 flex justify-center mb-2 text-lg fa-exchange-alt"></i>
        </x-slot>
        </x-sidebar.link>




        @if (!Auth::user()->is_assistant)
        <x-sidebar.link
            title="Students"
            href="{{ route('student') }}"
            :isActive="request()->routeIs('student')"
        >

        <x-slot name="icon">
            <i  class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-users"></i>
        </x-slot>
        </x-sidebar.link>
        @endif



        @if (!Auth::user()->is_assistant)
        <x-sidebar.link
        title="Reports"
        href="{{ route('reports') }}"
        :isActive="request()->routeIs('reports')"
    >

    <x-slot name="icon">
        <i  class="fa-solid w-6 h-6 flex justify-center mb-2 text-lg fa-file-lines"></i>

    </x-slot>
    </x-sidebar.link>
    @endif



    @endif
</x-perfect-scrollbar>
