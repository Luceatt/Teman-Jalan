@extends('layouts.app')

@section('title', 'History')

@section('content')
<div class="event-container">
    {{-- Back Button --}}
    <div class="back-wrapper">
        <a href="{{ route('history.index') }}" class="back-button">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
    </div>

    {{-- Place Header --}}
    <div class="event-header">
        <div class="place-header-content">
            <div class="place-image-wrapper">
                <img src="{{ $placeData['image_url'] }}" 
                     alt="{{ $placeData['place_name'] }}"
                     class="place-header-image">
            </div>
            <div class="place-header-info">
                <h1 class="event-title">{{ $placeData['place_name'] }}</h1>
                <p class="place-address">{{ $placeData['address'] }}</p>
                <p class="total-visits">Total Visits: <strong>{{ $placeData['total_visits'] }}</strong></p>
            </div>
        </div>
    </div>

    {{-- Events Loop --}}
    @foreach($eventsData as $eventData)
        <div class="place-card">
            {{-- Event Header --}}
            <div class="event-card-header">
                <div class="event-card-info">
                    <h2 class="event-card-title">{{ $eventData['event_name'] }}</h2>
                    <p class="event-card-description">{{ $eventData['event_description'] }}</p>
                </div>
                <div class="event-card-date">
                    <p class="event-date">{{ \Carbon\Carbon::parse($eventData['event_date'])->format('d F Y') }}</p>
                    <p class="event-time">
                        {{ \Carbon\Carbon::parse($eventData['visit_start'])->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($eventData['visit_end'])->format('H:i') }}
                    </p>
                </div>
            </div>

            <hr class="divider">

            {{-- Activities Loop --}}
            @foreach($eventData['activities'] as $activity)
                <div class="activity-section">
                    <h3 class="activity-title">{{ $activity['title'] }}</h3>

                    @if(count($activity['expenses']) > 0)
                        <div class="expenses-wrapper">
                            <p class="expenses-label">Sharing Expenses:</p>
                            
                            {{-- Expenses Table --}}
                            <div class="expenses-table-wrapper">
                                <table class="expenses-table">
                                    @foreach($activity['expenses'] as $expense)
                                        <tr>
                                            <td class="expense-description">{{ $expense['description'] }}</td>
                                            <td class="expense-participants">
                                                @foreach($expense['shares'] as $share)
                                                    @php
                                                        $user = \App\Models\User::find($share->id);
                                                    @endphp
                                                    @if($user)
                                                        @if($user->profile_picture)
                                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                                                 alt="{{ $user->name }}"
                                                                 title="{{ $user->name }}"
                                                                 class="expense-avatar">
                                                        @else
                                                            <div class="expense-avatar-placeholder" title="{{ $user->name }}">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="expense-count">{{ count($expense['shares']) }}x</td>
                                            <td class="expense-amount">Rp{{ number_format($expense['total_amount'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Event Total --}}
            <hr class="divider-thin">
            <div class="place-total">
                <div class="total-row">
                    <span class="total-label">Total Group Spent:</span>
                    <span class="total-amount">
                        Rp{{ number_format(collect($eventData['activities'])->flatMap(fn($a) => $a['expenses'])->sum('total_amount'), 0, ',', '.') }}
                    </span>
                </div>
                <div class="total-row">
                    <span class="total-label">You Spent:</span>
                    <span class="total-amount-user">
                        Rp{{ number_format($eventData['total_user_expense'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Grand Total --}}
    <div class="grand-total">
        <div class="grand-total-row">
            <span class="grand-total-label">Total Group Spent at This Place:</span>
            <span class="grand-total-amount">
                Rp{{ number_format(collect($eventsData)->flatMap(fn($e) => $e['activities'])->flatMap(fn($a) => $a['expenses'])->sum('total_amount'), 0, ',', '.') }}
            </span>
        </div>
        <div class="grand-total-row">
            <span class="grand-total-label">Total You Spent at This Place:</span>
            <span class="grand-total-amount-user">
                Rp{{ number_format($totalUserExpense, 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>

<style>
    
.event-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px 16px;
}

.back-wrapper {
    display: flex;
    justify-content: flex-start;
}

.back-button {
    display: inline-flex;
    align-items: center;
    color: #4b5563;
    text-decoration: none;
    margin-bottom: 24px;
    transition: color 0.2s;
}

.back-button:hover {
    color: #1f2937;
}

.event-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 24px;
    margin-bottom: 24px;
}

.place-header-content {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.place-image-wrapper {
    flex-shrink: 0;
}

.place-header-image {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.place-header-info {
    flex-grow: 1;
}

.event-title {
    font-size: 28px;
    font-weight: bold;
    color: #111827;
    margin: 0 0 8px 0;
    text-align: left;
}

.place-address {
    color: #6b7280;
    margin: 0 0 8px 0;
    text-align: left;
}

.total-visits {
    color: #374151;
    font-size: 14px;
    margin: 0;
    text-align: left;
}

.total-visits strong {
    color: #111827;
    font-weight: 600;
}

.place-card {
    background: linear-gradient(135deg, #fefce8 0%, #f0fdf4 100%);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid #fde047;
    padding: 24px;
    margin-bottom: 24px;
}

.event-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.event-card-info {
    flex-grow: 1;
}

.event-card-title {
    font-size: 24px;
    font-weight: bold;
    color: #111827;
    margin: 0 0 8px 0;
    text-align: left;
}

.event-card-description {
    color: #6b7280;
    margin: 0;
    text-align: left;
}

.event-card-date {
    text-align: right;
    flex-shrink: 0;
    margin-left: 20px;
}

.event-date {
    color: #374151;
    font-weight: 500;
    margin: 0 0 4px 0;
}

.event-time {
    color: #1f2937;
    font-weight: 600;
    margin: 0;
}

.divider {
    border: none;
    border-top: 1px solid #d1d5db;
    margin: 24px 0;
}

.divider-thin {
    border: none;
    border-top: 1px solid #d1d5db;
    margin: 16px 0;
}

.activity-section {
    text-align: left;
    margin-bottom: 24px;
}

.activity-title {
    font-size: 18px;
    font-weight: bold;
    color: #111827;
    background: #e9d5ff;
    display: inline-block;
    padding: 8px 16px;
    border-radius: 8px;
    margin: 0 0 12px 0;
    text-align: left;
}

.expenses-wrapper {
    padding-left: 0;
}

.expenses-label {
    color: #374151;
    font-weight: 500;
    margin: 0 0 12px 0;
    text-align: left;
}

.expenses-table-wrapper {
    padding: 0;
}

.expenses-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.expenses-table tr {
    background: rgba(255, 255, 255, 0.6);
}

.expenses-table td {
    padding: 12px;
    vertical-align: middle;
}

.expenses-table td:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.expenses-table td:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

.expense-description {
    color: #1f2937;
    font-weight: 500;
    width: 35%;
    text-align: left;
}

.expense-participants {
    display: flex;
    width: 35%;
    text-align: left;
}

.expense-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    margin-right: -8px;
}

.expense-avatar:last-child {
    margin-right: 0;
}

.expense-avatar-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #60a5fa, #a78bfa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    font-weight: 600;
    border: 2px solid white;
    margin-right: -8px;
}

.expense-avatar-placeholder:last-child {
    margin-right: 0;
}

.expense-count {
    color: #6b7280;
    width: 15%;
    text-align: left;
}

.expense-amount {
    color: #1f2937;
    font-weight: 600;
    text-align: right;
    width: 15%;
}

.place-total {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    padding: 16px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.total-row:last-child {
    margin-bottom: 0;
}

.total-label {
    color: #1f2937;
    font-weight: bold;
    font-size: 18px;
}

.total-amount {
    color: #111827;
    font-weight: bold;
    font-size: 18px;
}

.total-amount-user {
    color: #059669;
    font-weight: bold;
    font-size: 18px;
}

.grand-total {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 2px solid #d1d5db;
    padding: 24px;
}

.grand-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.grand-total-row:last-child {
    margin-bottom: 0;
}

.grand-total-label {
    color: #111827;
    font-weight: bold;
    font-size: 20px;
}

.grand-total-amount {
    color: #111827;
    font-weight: bold;
    font-size: 24px;
}

.grand-total-amount-user {
    color: #059669;
    font-weight: bold;
    font-size: 24px;
}

@media (max-width: 768px) {
    .place-header-content {
        flex-direction: column;
    }
    
    .event-card-header {
        flex-direction: column;
    }
    
    .event-card-date {
        text-align: left;
        margin-left: 0;
        margin-top: 12px;
    }
    
    .expenses-table {
        font-size: 14px;
    }
    
    .expenses-table td {
        padding: 8px;
    }
}
</style>
@endsection