<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-chart-line"></i> {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <h1><b><i class="fa-solid fa-book"></i> Total Books</b></h1>
            @if (!Auth::user()->is_admin)
            <h3 style="font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif">{{$totalBooks}}</h3>
            <x-nav-link  :href="route('bookList')" :active="request()->routeIs('bookList')">
                <b style="width: 200px" class="bg-slate-500 text-white p-5 rounded-xl"><i class="fa-solid fa-handshake-angle"></i> {{ __('Borrow Book') }}</b>
            </x-nav-link>
            @endif

            @if (Auth::user()->is_admin)
            <h3 style="font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif">{{$totalBooks}}</h3>
            <x-nav-link  :href="route('bookList')" :active="request()->routeIs('bookList')">
                <b style="width: 200px" class="bg-slate-500 text-white p-5 rounded-xl"><i class="fa-solid fa-eye"></i> {{ __('View All Books') }}</b>
            </x-nav-link>
            @endif
        </div>
    </div>
    <br>

    @if (!Auth::user()->is_admin)
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <h1><b><i class="fa-solid fa-circle-dollar-to-slot"></i> Total Fines</b></h1>
            <h3 style="font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif">₱ &nbsp;{{ number_format($totalFines, 2) ?? '0.00' }}</h3>
            <x-nav-link  :href="route('notifications')" :active="request()->routeIs('notifications')">
                <b style="width: 200px" class="bg-slate-500 text-white p-5 rounded-xl"><i class="fa-solid fa-circle-info"></i> {{ __('Details') }}</b>
            </x-nav-link>
        </div>
    </div>
    @endif


    @if (Auth::user()->is_admin)
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <h1><b><i class="fa-solid fa-bell"></i> Total Requests</b></h1>
            <h3 style="font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif">{{$totalRequests}}</h3>
            <x-nav-link  :href="route('requests')" :active="request()->routeIs('requests')">
                <b style="width: 200px" class="bg-slate-500 text-white p-5 rounded-xl"><i class="fa-solid fa-code-pull-request"></i> {{ __('Requests') }}</b>
            </x-nav-link>
        </div>
    </div>
    @endif
    <br>

    @if (Auth::user()->is_admin)
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <h1><b><i class="fa-solid fa-users"></i> Total Students</b></h1>
            <h3 style="font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif">{{$totalStudents}}</h3>
            <x-nav-link  :href="route('student')" :active="request()->routeIs('student')">
                <b style="width: 200px" class="bg-slate-500 text-white p-5 rounded-xl"><i class="fa-solid fa-graduation-cap"></i> {{ __('Students') }}</b>
            </x-nav-link>
        </div>
    </div>
    @endif

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
