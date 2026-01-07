@if($events && count($events) > 0)
    <div class="flex flex-col gap-5 mt-5">
        @foreach ($events as $event)
            {{-- Buat card menjadi clickable --}}
            <a href="{{ route('history.events.show', $event['event_id']) }}" class="block no-underline transform transition duration-200 hover:-translate-y-0.5 group">
                <div class="flex flex-col sm:flex-row justify-between items-stretch bg-white rounded-lg shadow p-6 group-hover:shadow-lg transition-shadow">
                    <div class="flex-1 text-left flex flex-col justify-between">
                        <h3 class="text-lg font-semibold m-0 text-gray-800">{{ $event['title'] }}</h3>
                        <p class="text-sm text-gray-600 mt-1.5 mb-5">{{ $event['places_visited'] }} Places Visited</p>
                        <p class="text-gray-500 mb-1">With:</p>
                        <div class="flex gap-1.5 m-0">
                            @foreach ($event['friends'] as $friend)
                                <img src="{{ $friend['profile_picture_url'] }}" alt="{{ $friend['name'] }}" 
                                     class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                            @endforeach
                        </div>
                    </div>

                    <div class="text-right flex flex-col justify-between items-end mt-4 sm:mt-0">
                        <div class="border border-gray-300 bg-[#fdf73e] rounded px-2.5 py-1 font-semibold m-0 text-gray-800">
                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                        </div>
                        <div class="text-right m-0">
                            <p class="m-0 mb-1 text-gray-500">You Spent:</p>
                            <p class="text-blue-600 font-bold text-base m-0">Rp{{ number_format($event['spent'], 0, ',', '.') }},00</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="text-center text-gray-400 mt-10">No History Yet</p>
@endif