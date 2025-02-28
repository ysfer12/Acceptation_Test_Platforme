<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Créer un Nouveau Quiz') }}
            </h2>
            <a href="{{ route('admin.quizzes') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 text-sm font-medium hover:bg-gray-400">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.quizzes.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="time_limit" class="block text-sm font-medium text-gray-700">Limite de temps (minutes)</label>
                                <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit', 30) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('time_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="passing_score" class="block text-sm font-medium text-gray-700">Score de réussite (%)</label>
                                <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" min="1" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('passing_score')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded mt-6">
                            <p class="text-gray-600 text-sm mb-2"><strong>Note:</strong> Une fois le quiz créé, vous pourrez y ajouter des questions.</p>
                            <p class="text-gray-600 text-sm">Par défaut, le nouveau quiz sera inactif jusqu'à ce que vous l'activiez explicitement.</p>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Créer le Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
