<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6 border-b pb-2">Calendar View</h3>

                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                        @php
                            $today = \Carbon\Carbon::today();
                            $weekStart = $today->copy()->startOfWeek();
                            
                            // Get appointments for this week grouped by date
                            $appointmentsByDate = $appointments->groupBy(function($appointment) {
                                return $appointment->scheduled_at->format('Y-m-d');
                            });
                        @endphp

                        @for ($i = 0; $i < 7; $i++)
                            @php
                                $currentDate = $weekStart->copy()->addDays($i);
                                $isToday = $currentDate->isToday();
                                $dateStr = $currentDate->format('Y-m-d');
                                $appointmentsForDay = $appointmentsByDate->get($dateStr, collect([]));
                            @endphp
                            
                            <div class="border rounded-lg overflow-hidden {{ $isToday ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
                                <div class="bg-gray-100 px-3 py-2 border-b">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ $currentDate->format('D') }}</span>
                                        <span class="text-sm {{ $isToday ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">{{ $currentDate->format('j M') }}</span>
                                    </div>
                                </div>
                                <div class="p-2 h-48 overflow-y-auto">
                                    @if($appointmentsForDay->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($appointmentsForDay as $appointment)
                                                <div class="bg-{{ $appointment->status === 'completed' ? 'green' : ($appointment->status === 'cancelled' ? 'red' : 'blue') }}-100 p-2 rounded border border-{{ $appointment->status === 'completed' ? 'green' : ($appointment->status === 'cancelled' ? 'red' : 'blue') }}-200 text-sm">
                                                    <p class="font-medium">{{ $appointment->scheduled_at->format('H:i') }}</p>
                                                    <p>{{ $appointment->user->first_name ?? '' }} {{ $appointment->user->last_name ?? $appointment->user->name }}</p>
                                                    <p class="text-xs text-gray-600">{{ $appointment->location }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="h-full flex items-center justify-center">
                                            <p class="text-sm text-gray-500">No appointments</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Month Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Monthly Overview</h3>
                        <div class="flex space-x-2">
                            <button id="prev-month" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span id="current-month" class="px-3 py-1 font-medium">{{ now()->format('F Y') }}</span>
                            <button id="next-month" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="month-calendar" class="grid grid-cols-7 gap-1 text-center">
                        <div class="py-2 bg-gray-100 font-medium text-sm">Sun</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Mon</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Tue</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Wed</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Thu</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Fri</div>
                        <div class="py-2 bg-gray-100 font-medium text-sm">Sat</div>
                        
                        <!-- Calendar days will be dynamically populated -->
                    </div>
                </div>
            </div>

            <!-- Appointments for Selected Day -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 id="selected-date-heading" class="text-lg font-semibold mb-4 border-b pb-2">Appointments for {{ now()->format('F j, Y') }}</h3>
                    
                    <div id="appointments-container">
                        <!-- Will be populated via JavaScript when a day is selected -->
                        <div class="text-center py-8 text-gray-500">
                            Select a date to view appointments
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentDate = new Date();
            const monthCalendar = document.getElementById('month-calendar');
            const currentMonthLabel = document.getElementById('current-month');
            const prevMonthBtn = document.getElementById('prev-month');
            const nextMonthBtn = document.getElementById('next-month');
            const selectedDateHeading = document.getElementById('selected-date-heading');
            const appointmentsContainer = document.getElementById('appointments-container');
            
            // Initial calendar rendering
            renderCalendar(currentDate);
            
            // Month navigation
            prevMonthBtn.addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            nextMonthBtn.addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            function renderCalendar(date) {
                // Update the month and year display
                currentMonthLabel.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });
                
                // Clear previous calendar days (except the header row)
                const dayElements = monthCalendar.querySelectorAll('.calendar-day');
                dayElements.forEach(el => el.remove());
                
                // Get first day of the month and total days
                const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                
                // Start with appropriate offset for the first day of the month
                const startOffset = firstDay.getDay(); // 0 for Sunday, 1 for Monday, etc.
                
                // Create empty cells for days of previous month
                for (let i = 0; i < startOffset; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'calendar-day py-3 bg-gray-50 text-gray-400 text-sm';
                    monthCalendar.appendChild(emptyCell);
                }
                
                // Create cells for current month
                const today = new Date();
                const isCurrentMonth = today.getMonth() === date.getMonth() && today.getFullYear() === date.getFullYear();
                
                for (let day = 1; day <= lastDay.getDate(); day++) {
                    const dayCell = document.createElement('div');
                    const isToday = isCurrentMonth && day === today.getDate();
                    
                    dayCell.className = `calendar-day py-3 ${isToday ? 'bg-blue-100 font-bold' : 'hover:bg-gray-100'} text-sm cursor-pointer`;
                    dayCell.textContent = day;
                    
                    // Date formatting for the API
                    const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    dayCell.dataset.date = formattedDate;
                    
                    // Add event listener to show appointments for this day
                    dayCell.addEventListener('click', function() {
                        fetchAppointmentsForDate(formattedDate);
                        
                        // Highlight the selected day
                        document.querySelectorAll('.calendar-day').forEach(cell => {
                            cell.classList.remove('bg-blue-200');
                            if (isToday) {
                                cell.classList.remove('bg-blue-100');
                            }
                        });
                        
                        dayCell.classList.add(isToday ? 'bg-blue-100' : 'bg-blue-200');
                    });
                    
                    monthCalendar.appendChild(dayCell);
                }
            }
            
            function fetchAppointmentsForDate(date) {
                // Format the date for display
                const displayDate = new Date(date);
                selectedDateHeading.textContent = `Appointments for ${displayDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`;
                
                // Show loading state
                appointmentsContainer.innerHTML = '<div class="flex justify-center py-8"><svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
                
                // Fetch appointments for this date
                fetch(`/staff/appointments/date?date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.appointments && data.appointments.length > 0) {
                            let html = '<div class="overflow-x-auto"><table class="min-w-full bg-white"><thead class="bg-gray-100"><tr>' +
                                '<th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>' +
                                '<th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>' +
                                '<th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>' +
                                '<th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>' +
                                '<th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>' +
                                '</tr></thead><tbody class="divide-y divide-gray-200">';
                            
                            data.appointments.forEach(appointment => {
                                const time = new Date(appointment.scheduled_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                                const status = appointment.status === 'scheduled' ? 'Scheduled' : 
                                             (appointment.status === 'completed' ? 'Completed' : 'Cancelled');
                                const statusClass = appointment.status === 'scheduled' ? 'blue' : 
                                                  (appointment.status === 'completed' ? 'green' : 'red');
                                
                                html += `<tr>
                                    <td class="py-3 px-4 text-sm">${time}</td>
                                    <td class="py-3 px-4">
                                        <a href="/staff/candidates/${appointment.user_id}" class="text-blue-600 hover:text-blue-900">
                                            ${appointment.user.first_name || ''} ${appointment.user.last_name || appointment.user.name}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm">${appointment.location}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${statusClass}-100 text-${statusClass}-800">${status}</span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <div class="flex space-x-2">
                                            <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openNotesModal(${appointment.id})">
                                                Notes
                                            </button>
                                            ${appointment.status === 'scheduled' ? 
                                              `<form action="/staff/appointments/${appointment.id}/complete" method="POST" class="inline">
                                                  @csrf
                                                  <button type="submit" class="text-green-600 hover:text-green-900">Complete</button>
                                              </form>` : ''}
                                        </div>
                                    </td>
                                </tr>`;
                            });
                            
                            html += '</tbody></table></div>';
                            appointmentsContainer.innerHTML = html;
                        } else {
                            appointmentsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">No appointments scheduled for this date</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching appointments:', error);
                        appointmentsContainer.innerHTML = '<div class="text-center py-8 text-red-500">Failed to load appointments. Please try again.</div>';
                    });
            }
            
            // Automatically select today's date on load
            const today = new Date();
            const formattedToday = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            fetchAppointmentsForDate(formattedToday);
        });
    </script>
</x-app-layout>