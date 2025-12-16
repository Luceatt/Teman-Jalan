@if($places && count($places) > 0)
    <div class="place-list">
        @foreach ($places as $place)
            <a href="{{ route('history.places.show', $place['place_id']) }}" class="place-card-link">
                <div class="place-card">
                    <div class="card-left">
                        <img src="{{ $place['image_url'] }}" alt="{{ $place['name'] }}" class="place-image">
                        <div class="place-info">
                            <h3 class="place-name">{{ $place['name'] }}</h3>
                            <p class="address">{{ $place['address'] }}</p>
                            <div class="activities-section">
                                <p class="activities-label">Activities:</p>
                                <div class="activities-badges">
                                    @foreach ($place['activities'] as $activity)
                                        <span class="activity-badge">{{ $activity }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-right">
                        <div class="visits-box">
                            {{ $place['total_visits'] }} Visits
                        </div>
                        <div class="last-visit">
                            <p>Last visit:</p>
                            <p class="visit-date">{{ \Carbon\Carbon::parse($place['last_visit'])->format('d/m/Y') }}</p>
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
.place-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform 0.2s;
}

.place-card-link:hover {
    transform: translateX(4px);
}

.place-card-link:hover .place-card {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.place-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
}

.place-card {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 30px;
}

.place-card .card-left {
    flex: 1;
    display: flex;
    flex-direction: row;
    gap: 20px;
    align-items: stretch;
}

.place-image {
    width: 120px; /* Ukuran gambar 120x120px */
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    flex-shrink: 0; /* Mencegah gambar menyusut */
}

.place-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.place-name {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.address {
    color: #555;
    font-size: 14px;
    margin: 6px 0 0 0;
}

.activities-section {
    margin: 0;
}

.activities-label {
    font-size: 14px;
    color: #333;
    font-weight: 500;
    margin: 0 0 6px 0; /* Jarak antara label dan badges */
}

.activities-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.activity-badge {
    display: inline-block;
    background-color: #e8f4ff;
    color: #007bff;
    font-size: 13px;
    font-weight: 500;
    padding: 6px; /* Padding 6px seperti yang diminta */
    border-radius: 6px;
    border: 1px solid #d0e7ff;
}

.place-card .card-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-end;
}

.visits-box {
    border: 1px solid #ccc;
    background: #fdf73eff;
    border-radius: 6px;
    padding: 5px 10px;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
}

.last-visit {
    text-align: right;
    margin: 0;
}

.last-visit p {
    margin: 0 0 4px 0;
    font-size: 14px;
}

.visit-date {
    color: #007bff;
    font-weight: 700;
    font-size: 16px;
}

.no-history {
    text-align: center;
    color: #999;
    margin-top: 40px;
}
</style>