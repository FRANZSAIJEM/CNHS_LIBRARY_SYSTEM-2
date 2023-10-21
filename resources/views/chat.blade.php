<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-message"></i> {{$student->name}}
            </h2>
        </div>
    </x-slot>


    @php
        session(['visited_history_page' => true]);
    @endphp


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="">
            <div style="">
                @foreach($chatMessages as $message)
                    <div class="bg-blue-500 p-5 rounded-lg shadow-sm text-white mb-3" style="min-width: min-content;">
                        <p>{{ $message->message }}</p>
                        <p>{{ $message->created_at }}</p>

                    </div>
                @endforeach
            </div>

            <div>
                <form method="POST" action="{{ route('sendChatMessage', $student->id) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" class="w-full p-2 rounded-lg" style="resize: none;" rows="3" placeholder="Type your message"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Send</button>
                    </div>
                </form>

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
