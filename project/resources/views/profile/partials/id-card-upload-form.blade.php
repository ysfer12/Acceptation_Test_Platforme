<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('ID Card') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload your identification card for verification.') }}
        </p>
    </header>

    @if (auth()->user()->id_card_path)
        <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ __('ID Card has been uploaded.') }}
        </div>
    @else
        <form method="post" action="{{ route('profile.upload-id') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <x-input-label for="id_card" :value="__('ID Card')" />
                <input id="id_card" name="id_card" type="file" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" required />
                <p class="mt-1 text-sm text-gray-500">{{ __('Accepted formats: JPEG, PNG, PDF (max 2MB)') }}</p>
                <x-input-error class="mt-2" :messages="$errors->get('id_card')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Upload') }}</x-primary-button>

                @if (session('status') === 'id-card-uploaded')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Uploaded.') }}</p>
                @endif
            </div>
        </form>
    @endif
</section>
