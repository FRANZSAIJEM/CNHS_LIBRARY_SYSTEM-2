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
            @if (Auth::user()->is_admin)
            <button class="bg-orange-400 text-white hover:bg-orange-500 duration-100 p-3 rounded-lg shadow-lg" id="cardViewBtn" onclick="toggleView('card')"><i class="fa fa-credit-card"></i> Card View</button>
            <button class="bg-orange-400 text-white hover:bg-orange-500 duration-100 p-3 rounded-lg shadow-lg" id="calendarViewBtn" onclick="toggleView('calendar')"><i class="fa fa-calendar"></i> Calendar View</button>

            @endif

            <div>
                <div id="cardView" class="view-container"> <br> <br>
                @if (count($userNotifications) > 0)
                @foreach ($userNotifications as $userNotification)
                @php
                    $carbonDate1 = \Carbon\Carbon::parse($userNotification->created_at);
                    $formattedDate1 = $carbonDate1->format('l, F jS, Y');
                @endphp
                    <div class="flex justify-between p-5 mb-5 rounded-md shadow-md bg-white dark:bg-dark-eval-1">
                        <div class="historyList">
                            <div>
                                <!-- Display the notification text -->
                                {{ $userNotification->notification->notification_text }}

                                on {{ $formattedDate1 }}
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
        <br> <br>






        <div id="calendarView" class="view-container" style="display: none;">
            <table id="calendar"></table>
        </div>









    </div>


   {{-- Loading Screen --}}
   <div id="loading-bar" class="loading-bar"></div>
  <style>


    @media (max-width: 1000px) and (max-height: 2000px) {

    }

    @media (max-width: 600px) and (max-height: 2000px) {

    }






    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      text-align: center;
      padding: 10px;
      border: 1px solid #ddd;
      height: 100px;
      width: 100px;

    }

    th {
      background-color: #f2f2f2;
    }

    button {
      padding: 5px 10px;
      font-size: 14px;
      cursor: pointer;
    }

    .today {
        box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
      background-color: #ffcc00; /* Yellow background for today's date */
    }

    .today::after {
      content: "Today"; /* Indicator for today's date */
      font-size: 10px;
      color: #333;
      display: block;
    }

    .dot {
      position: absolute;
      top: 5px;
      right: 5px;
      width: 8px;
      height: 8px;
      background-color: #4CAF50; /* Green dot color */
      border-radius: 50%;
    }



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
.event-dot {
        width: 10px;
        height: 10px;
        background-color: #337ab7; /* You can customize the color */
        border-radius: 50%;
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
    }

    .notification-card-enter {
  transform: scale(0);
  transition: transform 0.3s ease-in-out;
}

.notification-card-enter-active {
  transform: scale(1);
}

    </style>
<script>




function toggleView(view) {
    // Hide the notification card when switching to card view
    if (view === 'card') {
        notificationCard.style.display = 'none';
    }

    if (view === 'card') {
        document.getElementById('cardView').style.display = 'block';
        document.getElementById('calendarView').style.display = 'none';
    } else if (view === 'calendar') {
        document.getElementById('cardView').style.display = 'none';
        document.getElementById('calendarView').style.display = 'block';
    }
}



// document.addEventListener('DOMContentLoaded', function() {
//         var calendarEl = document.getElementById('calendar');

//         var calendar = new FullCalendar.Calendar(calendarEl, {
//             plugins: ['dayGrid'],
//             events: [
//                 { title: 'Test Event', start: '2023-11-30' }, // Replace with your actual date
//             ],
//             eventRender: function(info) {
//                 console.log(info.event); // Log event data for debugging
//                 var dot = document.createElement('div');
//                 dot.className = 'event-dot';
//                 info.el.appendChild(dot);
//             },
//             // Other calendar options and configurations
//         });

//         calendar.render();
//     });






// JavaScript to show and hide the loading bar
window.addEventListener('beforeunload', function () {
  document.getElementById('loading-bar').style.width = '100%';
});

window.addEventListener('load', function () {
  document.getElementById('loading-bar').style.width = '0';
});




// Extracted data from the Blade template
const userNotifications = @json($userNotifications);
function createCalendar(year, month) {
  const calendar = document.getElementById('calendar');
  const currentDate = new Date(year, month - 1, 1);
  const lastDay = new Date(year, month, 0).getDate();
  const daysInMonth = new Date(year, month, 0).getDate();
  const firstDayIndex = currentDate.getDay();

  const monthNames = [
    'January', 'February', 'March', 'April',
    'May', 'June', 'July', 'August',
    'September', 'October', 'November', 'December'
  ];

  const prevMonthButton = document.createElement('button');
  prevMonthButton.textContent = '< Previous Month';

  prevMonthButton.style.padding = '10px';
  prevMonthButton.style.borderRadius = '10px';
  prevMonthButton.style.margin = '10px';

  prevMonthButton.addEventListener('click', () => {
    const newDate = new Date(year, month - 2, 1);
    createCalendar(newDate.getFullYear(), newDate.getMonth() + 1);
  });

  const nextMonthButton = document.createElement('button');
  nextMonthButton.textContent = 'Next Month >';


  nextMonthButton.style.padding = '10px';
  nextMonthButton.style.borderRadius = '10px';
  nextMonthButton.style.margin = '10px';

  nextMonthButton.addEventListener('click', () => {
    const newDate = new Date(year, month, 1);
    createCalendar(newDate.getFullYear(), newDate.getMonth() + 1);
  });

  const header = document.createElement('div');

// Create a select dropdown for the year
const yearSelect = document.createElement('select');
yearSelect.style.padding = '10px';
yearSelect.style.width = '100px';
yearSelect.style.borderRadius = '10px';
yearSelect.style.margin = '10px';

// Create a select dropdown for the month
const monthSelect = document.createElement('select');
yearSelect.style.padding = '10px';
yearSelect.style.width = '100px';
yearSelect.style.borderRadius = '10px';
yearSelect.style.margin = '10px';



// Populate the select dropdown with years, adjust the range as needed
for (let i = 1957; i <= 2077; i++) {
  const option = document.createElement('option');
  option.value = i;
  option.text = i;
  yearSelect.appendChild(option);
}



for (let i = 0; i < 12; i++) {
  const option = document.createElement('option');
  option.value = i + 1;
  option.text = monthNames[i];
  monthSelect.appendChild(option);
}

// Set the default selected month
monthSelect.value = month;

// Set the default selected year
yearSelect.value = year;

// Add an event listener to the year select dropdown
yearSelect.addEventListener('change', () => {
  const selectedYear = parseInt(yearSelect.value, 10);
  createCalendar(selectedYear, month);
});

// Add an event listener to the month select dropdown
monthSelect.addEventListener('change', () => {
  const selectedMonth = parseInt(monthSelect.value, 10);
  createCalendar(year, selectedMonth);
});



header.appendChild(prevMonthButton);
header.appendChild(yearSelect);
header.appendChild(monthSelect); // Add the month select dropdown
header.appendChild(nextMonthButton);





  const table = document.createElement('table');
  const tr = document.createElement('tr');

  // Create day headers
  for (let i = 0; i < 7; i++) {
    const th = document.createElement('th');
    th.textContent = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][i];
    tr.appendChild(th);
  }
  table.appendChild(tr);

  // Create calendar days
  let day = 1;
  for (let i = 0; i < 6; i++) {
    const tr = document.createElement('tr');

    for (let j = 0; j < 7; j++) {
      const td = document.createElement('td');
      if (i === 0 && j < firstDayIndex) {
        // Empty cells before the first day
        td.textContent = '';
      } else if (day > lastDay) {
        // Empty cells after the last day
        td.textContent = '';
      } else {
        td.textContent = day;


        // Check if there are notifications for this day
        const notificationsForDay = userNotifications.filter(notification => {
          const notificationDate = new Date(notification.created_at);
          return (
            notificationDate.getDate() === day &&
            notificationDate.getMonth() === currentDate.getMonth() &&
            notificationDate.getFullYear() === currentDate.getFullYear()
          );
        });

        if (notificationsForDay.length > 0) {
    // Display all notifications in the cell
    const notificationList = document.createElement('ul');
    notificationList.style.listStyle = 'none';
    notificationsForDay.forEach(notification => {
    const listItem = document.createElement('li');
    listItem.textContent = notification.notification.notification_text;
    listItem.style.marginBottom = '20px'; // Add margin between notifications
    listItem.style.fontSize = '12px'; // Adjust font size
    listItem.style.backgroundColor = 'gray'; // Adjust font size
    listItem.style.borderRadius = '10px'; // Adjust font size
    listItem.style.color = 'white'; // Adjust font size
    listItem.style.padding = '10px'; // Adjust font size


    notificationList.appendChild(listItem);
  });



 // Toggle button
const toggleButton = document.createElement('button');

toggleButton.textContent = 'View Detail';
toggleButton.style.borderRadius = '10px';
toggleButton.style.marginLeft = '10px';

toggleButton.addEventListener('click', () => {
  toggleNotifications(td);
});

// Add hover effect
toggleButton.style.transition = 'background-color 0.3s';
toggleButton.style.backgroundColor = 'initial';

toggleButton.addEventListener('mouseover', () => {
  toggleButton.style.backgroundColor = '#ccc'; // Adjust the background color on hover
});

toggleButton.addEventListener('mouseout', () => {
  toggleButton.style.backgroundColor = 'initial';
});

// Append the button to the document
document.body.appendChild(toggleButton);


  // Hide the notification list by default
  notificationList.style.display = 'none';

  // Add the toggle button and notification list to the cell
  td.appendChild(toggleButton);
  td.appendChild(notificationList);

  // Add a dot to indicate data
  const dot = document.createElement('div');
  dot.className = 'dot';
  td.appendChild(dot);
}
        if (
          day === new Date().getDate() &&
          currentDate.getMonth() === new Date().getMonth() &&
          currentDate.getFullYear() === new Date().getFullYear()
        ) {
          // Highlight today's date
          td.classList.add('today');
        }
        day++;
      }
      tr.appendChild(td);
    }

    table.appendChild(tr);
  }

  // Clear existing content
  while (calendar.firstChild) {
    calendar.removeChild(calendar.firstChild);
  }

  // Append new content
  calendar.appendChild(header);
  calendar.appendChild(table);
}







// Create a card element
const notificationCard = document.createElement('div');
notificationCard.className = 'notification-card';
notificationCard.style.display = 'none'; // Hide the card by default
notificationCard.style.position = 'absolute';
notificationCard.style.zIndex = '1';
notificationCard.style.backgroundColor = 'white';
notificationCard.style.padding = '10px';
notificationCard.style.border = '1px solid #ccc';
notificationCard.style.borderRadius = '5px';
notificationCard.style.marginTop = '150px';
notificationCard.style.boxShadow = 'rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px';

// Append the card to the document
document.body.appendChild(notificationCard);

// Add this code for the animation
function toggleNotifications(td) {
  const dot = td.querySelector('.dot');
  const notificationsForDay = getNotificationsForDay(td); // Replace with your logic to get notifications for the clicked day

  if (notificationsForDay.length > 0) {
    // Populate the card with notifications
    notificationCard.innerHTML = '';

    // Get the date of the clicked cell
    const day = parseInt(td.textContent);
    const currentDate = new Date();
    currentDate.setDate(day);

     // Set the header with the date of the clicked cell
     const dayOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const headerText = `${currentDate.getDate()} ${dayOfWeek[currentDate.getDay()]}`;

    // Header element
    const header = document.createElement('div');
    header.style.fontWeight = 'bold';
    header.style.marginBottom = '10px';
    header.style.textAlign = 'center';
    header.style.fontSize = '30px';
    header.textContent = headerText;
    notificationCard.appendChild(header);


    // Close button container
    const closeButtonContainer = document.createElement('div');
    closeButtonContainer.style.display = 'flex';
    closeButtonContainer.style.justifyContent = 'flex-end'; // Align items to the right




    // Close button
    const closeButton = document.createElement('button');
    closeButton.textContent = 'Close';
    closeButton.style.marginBottom = '10px';
    closeButton.style.padding = '5px 10px';

    closeButton.style.border = 'none';
    closeButton.style.borderRadius = '10px';
    closeButton.style.cursor = 'pointer';

    // Hover effect styles
    closeButton.addEventListener('mouseover', () => {
        closeButton.style.backgroundColor = '#ccc';
        closeButton.style.transition = '0.2s';

    });

    closeButton.addEventListener('mouseout', () => {
    closeButton.style.backgroundColor = 'transparent';
    closeButton.style.transition = '0.2s';

    });




    // Add an event listener to the close button
    closeButton.addEventListener('click', () => {
    // Hide the card with animation when the close button is clicked
    notificationCard.style.transform = 'scale(0)';
    notificationCard.classList.remove('notification-card-enter');

    // Set a timeout to hide the card after the animation is complete
    setTimeout(() => {
        notificationCard.style.display = 'none';
    }, 300); // 300 milliseconds is the duration of the animation

    // Clear the content
    notificationCard.innerHTML = '';
    });

    closeButton.addEventListener('click', () => {
      // Hide the card with animation when the close button is clicked
      notificationCard.style.transform = 'scale(0)';
      notificationCard.classList.remove('notification-card-enter');
      // Set a timeout to hide the card after the animation is complete
      setTimeout(() => {
        notificationCard.style.display = 'none';
      }, 300); // 300 milliseconds is the duration of the animation
      // Clear the content
      notificationCard.innerHTML = '';
    });
        // Append the close button to its container
    closeButtonContainer.appendChild(closeButton)
    // Append the close button container to the card
    notificationCard.appendChild(closeButtonContainer);

    notificationsForDay.forEach(notification => {
      const listItem = document.createElement('div');
      listItem.textContent = notification.notification.notification_text;
      listItem.style.marginBottom = '10px'; // Add margin between notifications
      listItem.style.fontSize = '15px'; // Adjust font size
      listItem.style.backgroundColor = 'gray'; // Adjust background color
      listItem.style.borderRadius = '10px'; // Adjust border radius
      listItem.style.color = 'white'; // Adjust text color
      listItem.style.padding = '10px'; // Adjust padding

      notificationCard.appendChild(listItem);
    });

    // Show the card with animation
    notificationCard.style.display = 'block';
    notificationCard.style.transform = 'scale(0)'; // Set initial scale

    // Center the card on the screen
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    const cardWidth = notificationCard.offsetWidth;
    const cardHeight = notificationCard.offsetHeight;

    notificationCard.style.top = `${(screenHeight - cardHeight) / 2}px`;
    notificationCard.style.left = `${(screenWidth - cardWidth) / 2}px`;

    notificationCard.classList.add('notification-card-enter');

    // Set a timeout to remove the class after the animation is complete
    setTimeout(() => {
      notificationCard.classList.remove('notification-card-enter');
    }, 300); // 300 milliseconds is the duration of the animation

    // Apply final scale with transition
    notificationCard.style.transform = 'scale(1)';

    dot.style.display = 'none';
  } else {
    // Hide the card with animation
    notificationCard.style.transform = 'scale(0)';
    notificationCard.style.display = 'none';
    notificationCard.classList.remove('notification-card-enter');
    dot.style.display = 'block';
  }
}












function getNotificationsForDay(td) {
  // Extract date information from the clicked cell
  const day = parseInt(td.textContent, 10); // Assuming the day is present in the cell content
  const currentDate = new Date(); // Get the current date for the month and year

  // Filter userNotifications for the clicked day
  const notificationsForDay = userNotifications.filter(notification => {
    const notificationDate = new Date(notification.created_at);
    return (
      notificationDate.getDate() === day &&
      notificationDate.getMonth() === currentDate.getMonth() &&
      notificationDate.getFullYear() === currentDate.getFullYear()
    );
  });

  return notificationsForDay;
}






// Initial calendar creation
const currentYear = new Date().getFullYear();
const currentMonth = new Date().getMonth() + 1;
createCalendar(currentYear, currentMonth);














    </script>
</x-app-layout>
