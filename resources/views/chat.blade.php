<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-message"></i> {{ __('Chat') }}
            </h2>
        </div>
    </x-slot>


    @php
        session(['visited_history_page' => true]);
    @endphp



    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            @foreach($user->messages as $message)
                <div class="message">

                    @if(auth()->check() && $message->sender->id === auth()->user()->id)
                        <div style="display: grid;" class="justify-end">
                            <div class="bg-blue-500 text-white p-3 rounded-lg mb-1" style="display: inline-block;">
                                <span>{{ $message->message_content }}</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-200 text-black p-3 rounded-lg mb-1" style="display: inline-block;">
                            <span>{{ $message->message_content }}</span>
                        </div>
                    @endif
                </div>
            @endforeach

            <form action="{{ route('sendMessage') }}" method="post">
                @csrf
                <input class="border-1 rounded-lg  p-5 mt-10 w-full" type="text" name="message_content" placeholder="Type your message..." required>
                <input  type="hidden" name="receiver_id" value="{{ $user->id }}"> <!-- Add a hidden input for receiver_id -->
                <button class="float-right bg-slate-400 p-3 ps-5 pe-5 mt-3 text-white hover:bg-slate-500 duration-100 rounded-lg" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
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
