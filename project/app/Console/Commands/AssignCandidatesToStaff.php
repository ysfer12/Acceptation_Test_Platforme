<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AppointmentAssignmentService;

class AssignCandidatesToStaff extends Command
{
    protected $signature = 'appointments:assign';
    protected $description = 'Automatically assign candidates to available staff members';

    protected $assignmentService;

    public function __construct(AppointmentAssignmentService $assignmentService)
    {
        parent::__construct();
        $this->assignmentService = $assignmentService;
    }

    public function handle()
    {
        $this->info('Starting automatic candidate-staff assignment...');
        
        $assignments = $this->assignmentService->autoAssignCandidates();
        
        $this->info(count($assignments) . ' candidates assigned successfully.');
        
        foreach ($assignments as $index => $assignment) {
            $this->line(($index + 1) . '. ' . $assignment['candidate']->name . ' assigned to ' . $assignment['staff']->name . ' on ' . $assignment['appointment']->scheduled_at->format('Y-m-d H:i'));
        }
        
        return Command::SUCCESS;
    }
}