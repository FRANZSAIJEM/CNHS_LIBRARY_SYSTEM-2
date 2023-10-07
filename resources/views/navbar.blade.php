<x-app-layout>
    <x-slot name="header" >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="rounded-md shadow-md bg-white dark:bg-dark-eval-1 p-3 text-xl font-semibold leading-tight">
                <i class="fa-solid fa-clock"></i> {{ __('Time Remaining') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">


        <div style="display: grid; place-content: center;">
            @if ($acceptedRequest)
                <div class="text-center">Remaining time</div>
                <div class="text-center">
                    for the book you borrowed.
                </div>
                <div class="text-center" style="font-size: 40px;">
                    <div class="countdown-timer" data-target="{{ $acceptedRequest->timeDuration->date_return_seconds }}">
                        <!-- Countdown timer will be updated here using JavaScript -->
                    </div>
                </div>
            @else
                <div class="text-center">
                    No borrowed book.
                </div>
            @endif
        </div>


        {{-- {{$date_pickup}}
        {{$date_return}} --}}

        <h3 style="display: none; font-size: 50px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif"><div class="flex justify-center">₱ &nbsp; <div id="fines-container" style="display: none;"></div></div></h3>

        <div class="text-center mt-10">
            @if ($acceptedRequest)
                <a class="p-3 hover:text-slate-700 duration-100 text-slate-600" href="{{ route('viewBook', ['id' => $acceptedRequest->book->id]) }}"><b><i class="fa-solid fa-eye"></i> View Book</b></a>
            @endif
        </div>
    </div>

    <br>


    <script>
        function initializeCountdown() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    countdownElements.forEach(element => {
        const targetTimestamp = parseInt(element.getAttribute('data-target'), 10);
        updateCountdown(element, targetTimestamp);
    });
}

function updateCountdown(element, targetTimestamp) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const remainingTime = targetTimestamp - currentTimestamp;

    if (remainingTime > 0) {
        const hours = Math.floor(remainingTime / 3600);
        const minutes = Math.floor((remainingTime % 3600) / 60);
        const seconds = remainingTime % 60;
        element.innerHTML = hours + "h " + minutes + "m " + seconds + "s";

        // Show "0.00" in the fines container
        const finesContainer = document.getElementById('fines-container');
        finesContainer.style.display = 'block';
        finesContainer.innerHTML = '0.00';

        setTimeout(() => updateCountdown(element, targetTimestamp), 1000);
    } else {
        element.innerHTML = "Expired";

        // Show the fines container when the countdown expires
        const finesContainer = document.getElementById('fines-container');
        finesContainer.style.display = 'block';
    }
}

// Initialize the countdown timers when the page loads
window.addEventListener('DOMContentLoaded', initializeCountdown);

    </script>
</x-app-layout>
