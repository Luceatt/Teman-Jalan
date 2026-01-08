@extends('layouts.app')

@section('title', 'Edit Pengeluaran - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('rundowns.expenses.index', $event->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Pengeluaran
        </a>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Edit Pengeluaran</h1>
            </div>

            <form action="{{ route('rundowns.expenses.update', ['rundown' => $event->id, 'expense' => $expense->expense_id]) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Keterangan Pengeluaran</label>
                    <input type="text" name="description" id="description" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Contoh: Makan Siang, Tiket Masuk, Bensin"
                           value="{{ old('description', $expense->description) }}">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" required min="0" step="100"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="0"
                                   value="{{ old('amount', $expense->amount) }}">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                        <input type="date" name="expense_date" id="expense_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               value="{{ old('expense_date', \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d')) }}">
                        @error('expense_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Optional Activity Link -->
                <div>
                    <label for="activity_id" class="block text-sm font-medium text-gray-700">
                        Terkait dengan Aktivitas (Opsional)
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <select name="activity_id" id="activity_id"
                                class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                            <option value="">-- Tidak Ada / Umum --</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->activity_id }}" {{ (old('activity_id', $expense->activity_id) == $activity->activity_id) ? 'selected' : '' }}>
                                    {{ $activity->title }} ({{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Hubungkan pengeluaran ini dengan aktivitas tertentu di rundown.</p>
                    @error('activity_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pembayaran</h3>
                    
                    <div class="mb-4">
                        <label for="paid_by_user_id" class="block text-sm font-medium text-gray-700">Dibayar Oleh</label>
                        <select name="paid_by_user_id" id="paid_by_user_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @foreach($participants as $participant)
                                <option value="{{ $participant->user->id }}" {{ (old('paid_by_user_id', $expense->paid_by_user_id) == $participant->user->id) ? 'selected' : '' }}>
                                    {{ $participant->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('paid_by_user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ditanggung Oleh (Split)</label>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-h-60 overflow-y-auto">
                            <div class="flex items-center mb-3">
                                <input type="checkbox" id="select_all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="select_all" class="ml-2 block text-sm text-gray-900 font-medium">
                                    Pilih Semua Peserta
                                </label>
                            </div>
                            <div class="space-y-2">
                                @foreach($participants as $participant)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="split_with[]" value="{{ $participant->user->id }}"
                                               id="user_{{ $participant->user->id }}"
                                               class="participant-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ (
                                                   (is_array(old('split_with')) && in_array($participant->user->id, old('split_with'))) || 
                                                   (!old('split_with') && in_array($participant->user->id, $associatedUserIds))
                                                  ) ? 'checked' : '' }}>
                                        <label for="user_{{ $participant->user->id }}" class="ml-2 block text-sm text-gray-700">
                                            {{ $participant->user->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Biaya akan dibagi rata kepada peserta yang dipilih.</p>
                        @error('split_with')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('rundowns.expenses.index', $event->id) }}"
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateSelectAllState() {
        const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
        const allChecked = Array.from(participantCheckboxes).every(c => c.checked);
        const selectAll = document.getElementById('select_all');
        selectAll.checked = allChecked;
        selectAll.indeterminate = !allChecked && Array.from(participantCheckboxes).some(c => c.checked);
    }

    document.getElementById('select_all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
    participantCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllState);
    });
    
    // Initial State Check
    updateSelectAllState();
</script>
@endpush
@endsection
