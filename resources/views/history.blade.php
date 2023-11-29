<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-trash"></i> {{ __('History') }}
            </h2>
        </div>
    </x-slot>


    @php
        session(['visited_history_page' => true]);
    @endphp


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="">
            <div>

                @if (count($userNotifications) > 0)
                @foreach ($userNotifications as $userNotification)

                    <div class="flex justify-between p-5 mb-5 rounded-md shadow-md bg-white dark:bg-dark-eval-1">
                        <div class="historyList">
                            <div>
                                <!-- Display the notification text -->
                                {{ $userNotification->notification->notification_text }}
                            </div> <br>
                            <div class="me-5">
                                <h6 class="me-3 text-right" style="font-size: 13px;"></h6>
                                {{ \Carbon\Carbon::parse( $userNotification->created_at )->shortRelativeDiff() }}

                            </div>

                            <!-- Add a Clear button with a form to delete the notification -->
                        </div>

                        <div>
                            <form action="{{ route('clearNotification', ['id' => $userNotification->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-slate-600 p-3 rounded mt-3 hover:text-slate-900 duration-100 w-20" type="submit"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                    </div>
            @endforeach
            @else
                <!-- Message for no notifications in history -->
                <p>You have no history.</p>
            @endif
            </div>
        </div>
    </div>

   {{-- Loading Screen --}}
   <div id="loading-bar" class="loading-bar"></div>
  <style>
.loading-bar {
  width: 0;
  height: 5px; /* You can adjust the height as needed */
  background-color: #5fadff; /* Loading bar color */
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  transition: width 0.3s ease; /* Adjust the animation speed as needed */
}
    </style>
<script>
// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});


    </script>
</x-app-layout>
