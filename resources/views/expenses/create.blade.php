@extends('layouts.app')

@section('title', 'Tambah Pengeluaran - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('rundowns.expenses.index', $event->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Back to Expenses') }}
        </a>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">{{ __('Add New Expense') }}</h1>
            </div>

            <form action="{{ route('rundowns.expenses.store', $event->id) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Expense Description') }}</label>
                    <input type="text" name="description" id="description" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="{{ __('e.g., Lunch, Taxi, Tickets') }}"
                           value="{{ old('description') }}">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount (Rp)') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" required min="0" step="1"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="0"
                                   value="{{ old('amount') }}">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                        <input type="date" name="expense_date" id="expense_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               value="{{ old('expense_date', now()->format('Y-m-d')) }}">
                        @error('expense_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Optional Activity Link -->
                <div>
                    <label for="activity_id" class="block text-sm font-medium text-gray-700">
                        {{ __('Related Activity (Optional)') }}
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <select name="activity_id" id="activity_id"
                                class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                            <option value="">{{ __('-- None / General --') }}</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->activity_id }}" {{ old('activity_id') == $activity->activity_id ? 'selected' : '' }}>
                                    {{ $activity->title }} ({{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('activity_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Payment Details') }}</h3>
                    
                    <div class="mb-6">
                        <label for="paid_by_user_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Paid By') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($participants as $participant)
                                <label class="relative flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer focus-within:ring-2 focus-within:ring-blue-500">
                                    <input type="radio" name="paid_by_user_id" value="{{ $participant->user->id }}" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ (old('paid_by_user_id') == $participant->user->id || (Auth::id() == $participant->user->id && !old('paid_by_user_id'))) ? 'checked' : '' }}>
                                    
                                    <div class="ml-3 flex items-center">
                                       @if($participant->user->profile_photo_url)
                                            <img class="h-8 w-8 rounded-full" src="{{ $participant->user->profile_photo_url }}" alt="">
                                        @else
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500 text-white font-medium text-xs">
                                                {{ substr($participant->user->name, 0, 2) }}
                                            </span>
                                        @endif
                                        <span class="ml-2 block text-sm font-medium text-gray-900">
                                            {{ $participant->user->name }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                            
                            <!-- Include Creator if not in participants list (though logic suggests they should be added, let's safe guard) -->
                            @if(!$participants->contains('user_id', $event->creator_id))
                                <label class="relative flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer focus-within:ring-2 focus-within:ring-blue-500">
                                    <input type="radio" name="paid_by_user_id" value="{{ $event->creator_id }}" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ (old('paid_by_user_id') == $event->creator_id || (Auth::id() == $event->creator_id && !old('paid_by_user_id'))) ? 'checked' : '' }}>
                                    
                                    <div class="ml-3 flex items-center">
                                        @if($event->creator->profile_photo_url)
                                            <img class="h-8 w-8 rounded-full" src="{{ $event->creator->profile_photo_url }}" alt="">
                                        @else
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white font-medium text-xs">
                                                {{ substr($event->creator->name, 0, 2) }}
                                            </span>
                                        @endif
                                        <span class="ml-2 block text-sm font-medium text-gray-900">
                                            {{ $event->creator->name }}
                                        </span>
                                    </div>
                                </label>
                            @endif
                        </div>
                        @error('paid_by_user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Split With') }}</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="select_all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="select_all" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                                    {{ __('Select All') }}
                                </label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Creator Checkbox -->
                             @if(!$participants->contains('user_id', $event->creator_id))
                                <label class="relative flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="split_with[]" value="{{ $event->creator_id }}"
                                           class="participant-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ (is_array(old('split_with')) && in_array($event->creator_id, old('split_with'))) ? 'checked' : '' }}>
                                    <div class="ml-3 flex items-center">
                                         @if($event->creator->profile_photo_url)
                                            <img class="h-8 w-8 rounded-full" src="{{ $event->creator->profile_photo_url }}" alt="">
                                        @else
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white font-medium text-xs">
                                                {{ substr($event->creator->name, 0, 2) }}
                                            </span>
                                        @endif
                                        <span class="ml-2 text-sm text-gray-700">{{ $event->creator->name }}</span>
                                    </div>
                                </label>
                            @endif

                            @foreach($participants as $participant)
                                <label class="relative flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="split_with[]" value="{{ $participant->user->id }}"
                                           class="participant-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ (is_array(old('split_with')) && in_array($participant->user->id, old('split_with'))) || (!old('split_with')) ? 'checked' : '' }}>
                                    <div class="ml-3 flex items-center">
                                        @if($participant->user->profile_photo_url)
                                            <img class="h-8 w-8 rounded-full" src="{{ $participant->user->profile_photo_url }}" alt="">
                                        @else
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500 text-white font-medium text-xs">
                                                {{ substr($participant->user->name, 0, 2) }}
                                            </span>
                                        @endif
                                        <span class="ml-2 text-sm text-gray-700">{{ $participant->user->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500">{{ __('Cost will be split equally among selected participants.') }}</p>
                        @error('split_with')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('rundowns.expenses.index', $event->id) }}"
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Save Expense') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('select_all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
    participantCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(participantCheckboxes).every(c => c.checked);
            const selectAll = document.getElementById('select_all');
            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && Array.from(participantCheckboxes).some(c => c.checked);
        });
    });
</script>
@endpush
@endsection
