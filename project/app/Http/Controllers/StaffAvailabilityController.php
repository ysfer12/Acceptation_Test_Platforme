<?php

namespace App\Http\Controllers;

use App\Models\StaffAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffAvailabilityController extends Controller
{
    /**
     * Display the staff availability management page.
     */
    public function index()
    {
        $staffId = auth()->id();
        
        // Get recurring availabilities
        $recurringAvailabilities = StaffAvailability::where('staff_id', $staffId)
            ->where('is_recurring', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Get one-time availabilities for the next 30 days
        $oneTimeAvailabilities = StaffAvailability::where('staff_id', $staffId)
            ->where('is_recurring', false)
            ->whereDate('date', '>=', Carbon::today())
            ->whereDate('date', '<=', Carbon::today()->addDays(30))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        return view('staff.availability', compact('recurringAvailabilities', 'oneTimeAvailabilities'));
    }
    
    /**
     * Store a new availability record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'availability_type' => 'required|in:recurring,one-time',
            'day_of_week' => 'required_if:availability_type,recurring|nullable|integer|min:0|max:6',
            'date' => 'required_if:availability_type,one-time|nullable|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
        StaffAvailability::create([
            'staff_id' => auth()->id(),
            'day_of_week' => $validated['availability_type'] === 'recurring' ? $validated['day_of_week'] : null,
            'date' => $validated['availability_type'] === 'one-time' ? $validated['date'] : null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_recurring' => $validated['availability_type'] === 'recurring',
        ]);
        
        return redirect()->route('staff.availability')
            ->with('status', 'Availability added successfully.');
    }
    
    /**
     * Delete an availability record.
     */
    public function destroy(StaffAvailability $availability)
    {
        // Check if the availability belongs to the logged-in staff
        if ($availability->staff_id !== auth()->id()) {
            return redirect()->route('staff.availability')
                ->with('error', 'You are not authorized to delete this availability.');
        }
        
        $availability->delete();
        
        return redirect()->route('staff.availability')
            ->with('status', 'Availability removed successfully.');
    }
}