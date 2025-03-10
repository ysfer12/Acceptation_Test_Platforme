<?php

namespace App\Services;

use App\Models\User;
use App\Models\Appointment;
use App\Models\StaffAvailability;
use App\Models\UserQuiz;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class AppointmentAssignmentService
{
    /**
     * Find available staff members
     */
    public function findAvailableStaff(Carbon $date = null)
    {
        // If no date is provided, use the current date
        $date = $date ?: Carbon::today();
        
        // Get day of week for the date
        $dayOfWeek = $date->dayOfWeek;
        
        // Get all staff members
        $staffMembers = User::where('role', 'staff')->get();
        
        // Array to store staff with availability
        $availableStaff = [];
        
        foreach ($staffMembers as $staff) {
            // Get staff's recurring availability for this day of week
            $availabilities = StaffAvailability::where('staff_id', $staff->id)
                ->where(function($query) use ($dayOfWeek, $date) {
                    $query->where(function($q) use ($dayOfWeek) {
                        $q->where('day_of_week', $dayOfWeek)
                          ->where('is_recurring', true);
                    })->orWhere(function($q) use ($date) {
                        $q->where('date', $date->toDateString())
                          ->where('is_recurring', false);
                    });
                })
                ->orderBy('start_time')
                ->get();
            
            // If staff has no availability for this day, skip
            if ($availabilities->isEmpty()) {
                continue;
            }
            
            // Get staff's existing appointments for this date
            $appointments = Appointment::where('staff_id', $staff->id)
                ->whereDate('scheduled_at', $date)
                ->get();
            
            // Calculate available time slots
            $availableSlots = $this->calculateAvailableSlots($availabilities, $appointments);
            
            // If no available slots, skip
            if (empty($availableSlots)) {
                continue;
            }
            
            // Add staff to available staff array
            $availableStaff[] = [
                'staff' => $staff,
                'appointment_count' => $staff->appointments()->where('status', 'scheduled')->count(),
                'available_slots' => $availableSlots,
            ];
        }
        
        // Sort by appointment count (fewer first) to distribute workload evenly
        usort($availableStaff, function($a, $b) {
            return $a['appointment_count'] <=> $b['appointment_count'];
        });
        
        return $availableStaff;
    }
    
    /**
     * Calculate available time slots based on availabilities and booked appointments
     */
    private function calculateAvailableSlots($availabilities, $appointments)
    {
        $availableSlots = [];
        
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);
            
            // Create slots of 1 hour each
            $slotStart = clone $startTime;
            
            while ($slotStart->lt($endTime)) {
                $slotEnd = (clone $slotStart)->addHour();
                
                // Adjust last slot if it exceeds end time
                if ($slotEnd->gt($endTime)) {
                    $slotEnd = clone $endTime;
                }
                
                // Skip if slot is less than 30 minutes
                if ($slotEnd->diffInMinutes($slotStart) < 30) {
                    break;
                }
                
                // Check if slot overlaps with any existing appointment
                $isAvailable = true;
                foreach ($appointments as $appointment) {
                    $apptStart = Carbon::parse($appointment->scheduled_at);
                    $apptEnd = (clone $apptStart)->addHour(); // Assuming 1 hour appointments
                    
                    // Check for overlap
                    if ($slotStart->lt($apptEnd) && $slotEnd->gt($apptStart)) {
                        $isAvailable = false;
                        break;
                    }
                }
                
                if ($isAvailable) {
                    $availableSlots[] = [
                        'start' => clone $slotStart,
                        'end' => clone $slotEnd,
                    ];
                }
                
                $slotStart->addHour();
            }
        }
        
        return $availableSlots;
    }
    
    /**
     * Automatically assign candidates to staff based on availability
     */
    public function autoAssignCandidates()
    {
        // Get candidates who passed the quiz but don't have an appointment yet
        $candidates = User::where('role', 'candidate')
        ->whereExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('user_quizzes')
                  ->whereColumn('user_quizzes.user_id', 'users.id')
                  ->where('user_quizzes.passed', true);
        })
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('appointments')
                  ->whereColumn('appointments.user_id', 'users.id')
                  ->where('appointments.status', 'scheduled');
        })
        ->get();
        
        $assignmentResults = [];
        
        foreach ($candidates as $candidate) {
            // Look for appointments in the next 7 days
            for ($day = 0; $day < 7; $day++) {
                $date = Carbon::today()->addDays($day);
                
                // Find available staff for this date
                $availableStaff = $this->findAvailableStaff($date);
                
                if (!empty($availableStaff)) {
                    // Get the staff member with fewest appointments
                    $staff = $availableStaff[0]['staff'];
                    $slot = $availableStaff[0]['available_slots'][0];
                    
                    // Create a datetime for the appointment
                    $appointmentDateTime = Carbon::parse($date->toDateString() . ' ' . $slot['start']->format('H:i:s'));
                    
                    // Create the appointment
                    $appointment = Appointment::create([
                        'user_id' => $candidate->id,
                        'staff_id' => $staff->id,
                        'scheduled_at' => $appointmentDateTime,
                        'location' => 'On-site assessment center', // Default location
                        'status' => 'scheduled',
                        'notes' => 'Automatically assigned based on staff availability.',
                    ]);
                    
                    $assignmentResults[] = [
                        'candidate' => $candidate,
                        'staff' => $staff,
                        'appointment' => $appointment,
                    ];
                    
                    // Move to next candidate
                    break;
                }
            }
        }
        
        return $assignmentResults;
    }
}