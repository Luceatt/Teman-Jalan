@if($events && count($events) > 0)
    <div class="event-list">
        @foreach ($events as $event)
            {{-- Buat card menjadi clickable --}}
            <a href="{{ route('history.events.show', $event['event_id']) }}" class="event-card-link">
                <div class="event-card">
                    <div class="card-left">
                        <h3 class="event-title">{{ $event['title'] }}</h3>
                        <p class="visited">{{ $event['places_visited'] }} Places Visited</p>
                        <p class="with">With:</p>
                        <div class="friends">
                            @foreach ($event['friends'] as $friend)
                                <img src="{{ $friend['profile_picture_url'] }}" alt="{{ $friend['name'] }}" class="friend-avatar">
                            @endforeach
                        </div>
                    </div>

                    <div class="card-right">
                        <div class="date-box">
                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                        </div>
                        <div class="spent">
                            <p>You Spent:</p>
                            <p class="amount">Rp{{ number_format($event['spent'], 0, ',', '.') }},00</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="no-history">No History Yet</p>
@endif

<style>
.event-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
}

/* Wrapper link agar seluruh card bisa diklik */
.event-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.event-card-link:hover {
    transform: translateY(-2px);
}

.event-card-link:hover .event-card {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.event-card {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 30px;
    cursor: pointer;
    transition: box-shadow 0.2s ease;
}

.card-left {
    flex: 1;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.event-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #333;
}

.visited {
    color: #555;
    font-size: 14px;
    margin: 6px 0 20px 0;
}

.with {
    margin: 0 0 4px 0;
    color: #666;
}

.friends {
    display: flex;
    gap: 6px;
    margin: 0;
}

.friend-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 0 3px rgba(0,0,0,0.2);
}

.card-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-end;
}

.date-box {
    border: 1px solid #ccc;
    background: #fdf73eff;
    border-radius: 6px;
    padding: 5px 10px;
    font-weight: 600;
    margin: 0;
    color: #333;
}

.spent {
    text-align: right;
    margin: 0;
}

.spent p {
    margin: 0 0 4px 0;
    color: #666;
}

.spent .amount {
    color: #007bff;
    font-weight: 700;
    font-size: 16px;
    margin: 0;
}

.no-history {
    text-align: center;
    color: #999;
    margin-top: 40px;
}
</style>