@if($places && count($places) > 0)
    <div class="flex flex-col gap-5 mt-5">
        @foreach ($places as $place)
            <a href="{{ route('history.places.show', $place['place_id']) }}" class="block no-underline transform transition duration-200 hover:translate-x-1 group">
                <div class="flex flex-col sm:flex-row justify-between items-stretch bg-white rounded-lg shadow p-6 group-hover:shadow-lg transition-shadow">
                    <div class="flex-1 flex flex-row gap-5 items-stretch">
                        <img src="{{ $place['image_url'] }}" alt="{{ $place['name'] }}" 
                             class="w-28 h-28 object-cover rounded-lg shadow-sm flex-shrink-0">
                        <div class="flex-1 flex flex-col justify-between">
                            <h3 class="text-lg font-semibold m-0 text-gray-800">{{ $place['name'] }}</h3>
                            <p class="text-sm text-gray-600 mt-1.5 mb-0">{{ $place['address'] }}</p>
                            <div class="m-0">
                                <p class="text-sm text-gray-800 font-medium mb-1.5 mt-2">Activities:</p>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach ($place['activities'] as $activity)
                                        <span class="inline-block bg-blue-50 text-blue-600 text-xs font-medium px-2 py-1 rounded-md border border-blue-100">
                                            {{ $activity }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right flex flex-col justify-between items-end mt-4 sm:mt-0 ml-4">
                        <div class="border border-gray-300 bg-[#fdf73e] rounded px-2.5 py-1 font-semibold m-0 whitespace-nowrap text-gray-800">
                            {{ $place['total_visits'] }} Visits
                        </div>
                        <div class="text-right m-0">
                            <p class="m-0 mb-1 text-sm text-gray-600">Last visit:</p>
                            <p class="text-blue-600 font-bold text-base m-0">{{ \Carbon\Carbon::parse($place['last_visit'])->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="text-center text-gray-400 mt-10">No History Yet</p>
@endif