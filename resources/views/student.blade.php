<x-app-layout>
    <x-slot name="header" >
        <div class="overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1 p-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                <i class="fa-solid fa-users"></i> {{ __('Students') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="text-center ">
            <div>
                @foreach ($students as $student)
                <div style="margin: 30px; border-radius: 10px; box-shadow: 10px 10px 20px 5px rgba(0, 0, 0, 0.298); background-color: rgb(4, 51, 71); padding: 20px">
                    <div style="background-position: center center; border-radius: 5px; width: 211px; ">
                        <div style="margin-bottom: 20px;">
                            <b>Full Name</b> <br> {{$student->name}} <br>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <b>ID Number</b> <br> {{$student->id_number}} <br>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <b>Email</b> <br> {{$student->email}} <br>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <b>Contact Number</b> <br> {{$student->contact}} <br>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <b>Grade Level </b> <br> {{$student->grade_level}} <br>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <b>Total Fines</b> <br>₱ &nbsp;{{ number_format($student->totalFines, 2) ?? '0.00' }} <br>
                        </div>

                        <div>
                            <form class="toggle-form" data-student-id="{{ $student->id }}" style="display: inline;">
                                @csrf
                                <button class="toggle-button" type="button"
                                        style="width: 210px; padding: 10px; border-radius: 5px; background-color: {{ $student->is_disabled ? 'red' : 'green' }}; color: white;">
                                    {{ $student->is_disabled ? 'Account Disabled' : 'Account Enabled' }}
                                </button>
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

const toggleButtons = document.querySelectorAll('.toggle-button');

toggleButtons.forEach(button => {
    button.addEventListener('click', async () => {
        const form = button.closest('.toggle-form');
        const studentId = form.dataset.studentId;

        try {
            const response = await fetch(`{{ route('toggleAccountStatus', ['id' => '__STUDENT_ID__']) }}`.replace('__STUDENT_ID__', studentId), {
                method: 'POST',
                body: new FormData(form),
            });

            if (response.ok) {
                // Toggle the button text and background color
                const currentStatus = button.textContent.includes('Enabled') ? 'Enabled' : 'Disabled';
                const newStatus = currentStatus === 'Enabled' ? 'Disabled' : 'Enabled';
                const newColor = currentStatus === 'Enabled' ? 'red' : 'green';

                button.textContent = `Account ${newStatus}`;
                button.style.backgroundColor = newColor;
            }
        } catch (error) {
            console.error('Error toggling account status:', error);
        }
    });
});
    </script>
</x-app-layout>
