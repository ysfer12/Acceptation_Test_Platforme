<!-- resources/views/staff/evaluation-create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Evaluation for') }} {{ $candidate->first_name }} {{ $candidate->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('staff.evaluation.store') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $candidate->id }}">
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id ?? '' }}">

                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Evaluation Type</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <label class="flex items-center p-4 border rounded-md cursor-pointer transition-colors @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="interview" class="h-4 w-4 text-blue-600 mr-2" checked>
                                <div>
                                    <div class="font-medium">Interview</div>
                                    <div class="text-sm text-gray-500">Communication and cultural fit</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-md cursor-pointer transition-colors @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="technical" class="h-4 w-4 text-blue-600 mr-2">
                                <div>
                                    <div class="font-medium">Technical</div>
                                    <div class="text-sm text-gray-500">Technical skills assessment</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-md cursor-pointer transition-colors @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="soft_skills" class="h-4 w-4 text-blue-600 mr-2">
                                <div>
                                    <div class="font-medium">Soft Skills</div>
                                    <div class="text-sm text-gray-500">Teamwork and adaptability</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-md cursor-pointer transition-colors @error('type') border-red-500 @enderror">
                                <input type="radio" name="type" value="overall" class="h-4 w-4 text-blue-600 mr-2">
                                <div>
                                    <div class="font-medium">Overall</div>
                                    <div class="text-sm text-gray-500">Final assessment</div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Skills Assessment Form - Changes based on selected type -->
                    <div id="assessment-forms" class="mb-6">
                        <!-- Interview Form -->
                        <div id="interview-form" class="evaluation-form">
                            <h3 class="text-lg font-medium mb-4">Interview Skills Assessment</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Communication Skills</label>
                                    <div class="flex items-center gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="score-option">
                                                <input type="radio" name="criteria[communication]" value="{{ $i }}" class="sr-only">
                                                <span class="block w-10 h-10 rounded-full flex items-center justify-center cursor-pointer text-sm border">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cultural Fit</label>
                                    <div class="flex items-center gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="score-option">
                                                <input type="radio" name="criteria[cultural_fit]" value="{{ $i }}" class="sr-only">
                                                <span class="block w-10 h-10 rounded-full flex items-center justify-center cursor-pointer text-sm border">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Problem Solving</label>
                                    <div class="flex items-center gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="score-option">
                                                <input type="radio" name="criteria[problem_solving]" value="{{ $i }}" class="sr-only">
                                                <span class="block w-10 h-10 rounded-full flex items-center justify-center cursor-pointer text-sm border">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Experience Relevance</label>
                                    <div class="flex items-center gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="score-option">
                                                <input type="radio" name="criteria[experience]" value="{{ $i }}" class="sr-only">
                                                <span class="block w-10 h-10 rounded-full flex items-center justify-center cursor-pointer text-sm border">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Technical Form (initially hidden) -->
                        <div id="technical-form" class="evaluation-form hidden">
                            <!-- Similar structure with technical criteria -->
                        </div>
                        
                        <!-- Soft Skills Form (initially hidden) -->
                        <div id="soft-skills-form" class="evaluation-form hidden">
                            <!-- Similar structure with soft skills criteria -->
                        </div>
                        
                        <!-- Overall Form (initially hidden) -->
                        <div id="overall-form" class="evaluation-form hidden">
                            <h3 class="text-lg font-medium mb-4">Overall Assessment</h3>
                            
                            <div class="mb-4">
                                <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Overall Score (0-100)</label>
                                <input type="number" id="score" name="score" min="0" max="100" value="70" class="w-full rounded-md border-gray-300">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="strengths" class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
                            <textarea id="strengths" name="strengths" rows="4" class="w-full rounded-md border-gray-300" placeholder="Candidate's key strengths..."></textarea>
                        </div>
                        
                        <div>
                            <label for="weaknesses" class="block text-sm font-medium text-gray-700 mb-1">Areas for Improvement</label>
                            <textarea id="weaknesses" name="weaknesses" rows="4" class="w-full rounded-md border-gray-300" placeholder="Areas where the candidate could improve..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-1">Additional Comments</label>
                        <textarea id="comments" name="comments" rows="4" class="w-full rounded-md border-gray-300" placeholder="Additional observations and comments..."></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Final Result</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-4 border border-green-200 bg-green-50 rounded-md cursor-pointer hover:bg-green-100 transition-colors @error('result') border-red-500 @enderror">
                                <input type="radio" name="result" value="pass" class="h-4 w-4 text-green-600 mr-2" checked>
                                <div>
                                    <div class="font-medium text-green-800">Pass</div>
                                    <div class="text-sm text-green-600">Candidate meets requirements</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-red-200 bg-red-50 rounded-md cursor-pointer hover:bg-red-100 transition-colors @error('result') border-red-500 @enderror">
                                <input type="radio" name="result" value="fail" class="h-4 w-4 text-red-600 mr-2">
                                <div>
                                    <div class="font-medium text-red-800">Fail</div>
                                    <div class="text-sm text-red-600">Candidate does not meet requirements</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-yellow-200 bg-yellow-50 rounded-md cursor-pointer hover:bg-yellow-100 transition-colors @error('result') border-red-500 @enderror">
                                <input type="radio" name="result" value="pending" class="h-4 w-4 text-yellow-600 mr-2">
                                <div>
                                    <div class="font-medium text-yellow-800">Pending</div>
                                    <div class="text-sm text-yellow-600">Need more information</div>
                                </div>
                            </label>
                        </div>
                        @error('result')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('staff.candidate.profile', $candidate->id) }}" class="px-4 py-2 bg-gray-200 rounded-md">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit Evaluation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        .score-option input:checked + span {
            @apply bg-blue-600 text-white border-blue-600;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const evaluationForms = document.querySelectorAll('.evaluation-form');
            
            // Handle form display based on evaluation type
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    evaluationForms.forEach(form => form.classList.add('hidden'));
                    document.getElementById(`${this.value}-form`).classList.remove('hidden');
                });
            });
            
            // Style for rating selection
            const scoreOptions = document.querySelectorAll('.score-option input');
            scoreOptions.forEach(option => {
                option.addEventListener('change', function() {
                    // Find all options in the same group
                    const name = this.getAttribute('name');
                    const groupOptions = document.querySelectorAll(`input[name="${name}"]`);
                    
                    // Remove styling from all options in the group
                    groupOptions.forEach(opt => {
                        opt.nextElementSibling.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                    });
                    
                    // Apply styling to selected option
                    this.nextElementSibling.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                });
            });
        });
    </script>
</x-app-layout>