<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-trash"></i> {{ __('History') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="">
            <div>
                @foreach ($userNotifications as $userNotification)
                <div>
                    <div class="flex justify-between shadow-md bg-white dark:bg-dark-eval-1" style="margin: 13px; border-radius: 10px; background-color: rgb(255, 255, 255); padding: 20px">
                        <div class="historyList">
                            <div>
                                <!-- Display the notification text -->
                                {{ $userNotification->notification->notification_text }}
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
                </div>
            @endforeach
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
