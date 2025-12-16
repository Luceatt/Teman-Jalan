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

    {{-- Event Header --}}
    <div class="event-header">
        <div class="event-title-row">
            <h1 class="event-title">{{ $event->event_name }}</h1>
            <div class="event-date-wrapper">
                <p class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}</p>
                <span class="status-badge">Completed</span>
            </div>
        </div>
        
        <p class="event-description">{{ $event->description }}</p>

        {{-- Participants --}}
        <div class="participants-section">
            <p class="participants-label">With:</p>
            <div class="participants-grid">
                @foreach($event->participants as $participant)
                    <div class="participant-item">
                        @if($participant->user->profile_picture)
                            <img src="{{ asset('storage/' . $participant->user->profile_picture) }}" 
                                 alt="{{ $participant->user->name }}"
                                 class="participant-avatar">
                        @else
                            <div class="participant-avatar-placeholder">
                                {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="participant-name">{{ $participant->user->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Places Loop --}}
    @foreach($placesData as $placeData)
        <div class="place-card">
            {{-- Place Header --}}
            <div class="place-header">
                <div class="place-image-wrapper">
                    <img src="{{ $placeData['image_url'] }}" 
                         alt="{{ $placeData['place_name'] }}"
                         class="place-image">
                </div>
                <div class="place-info">
                    <div class="place-details">
                        <div>
                            <h2 class="place-name">{{ $placeData['place_name'] }}</h2>
                            <p class="place-address">{{ $placeData['address'] }}</p>
                        </div>
                        <div class="place-time">
                            <p>
                                {{ \Carbon\Carbon::parse($placeData['visit_start'])->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($placeData['visit_end'])->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="divider">

            {{-- Activities Loop --}}
            @foreach($placeData['activities'] as $activity)
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
                                                        $user = $event->participants->firstWhere('user_id', $share->id)->user ?? null;
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

            {{-- Place Total --}}
            <hr class="divider-thin">
            <div class="place-total">
                <div class="total-row">
                    <span class="total-label">Total Group Spent:</span>
                    <span class="total-amount">
                        Rp{{ number_format(collect($placeData['activities'])->flatMap(fn($a) => $a['expenses'])->sum('total_amount'), 0, ',', '.') }}
                    </span>
                </div>
                <div class="total-row">
                    <span class="total-label">You Spent:</span>
                    <span class="total-amount-user">
                        Rp{{ number_format($placeData['total_user_expense'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Grand Total --}}
    <div class="grand-total">
        <div class="grand-total-row">
            <span class="grand-total-label">Total Group Spent at This Event:</span>
            <span class="grand-total-amount">
                Rp{{ number_format(collect($placesData)->flatMap(fn($p) => $p['activities'])->flatMap(fn($a) => $a['expenses'])->sum('total_amount'), 0, ',', '.') }}
            </span>
        </div>
        <div class="grand-total-row">
            <span class="grand-total-label">Total You Spent as This Event:</span>
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

.event-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.event-title {
    font-size: 28px;
    font-weight: bold;
    color: #111827;
    margin: 0;
    text-align: left;
}

.event-description {
    color: #6b7280;
    margin: 0 0 16px 0;
    text-align: left;
}

.event-date-wrapper {
    text-align: right;
}

.event-date {
    color: #374151;
    font-weight: 500;
    margin: 0 0 8px 0;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #d1fae5;
    color: #065f46;
    font-size: 14px;
    font-weight: 600;
    border-radius: 20px;
}

.participants-section {
    border-top: 1px solid #e5e7eb;
    padding-top: 16px;
}

.participants-label {
    color: #374151;
    font-weight: 500;
    margin: 0 0 12px 0;
    text-align: left;
}

.participants-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.participant-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.participant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.participant-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #60a5fa, #a78bfa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    border: 2px solid #e5e7eb;
}

.participant-name {
    font-size: 14px;
    color: #374151;
}

.place-card {
    background: linear-gradient(135deg, #fefce8 0%, #f0fdf4 100%);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid #fde047;
    padding: 24px;
    margin-bottom: 24px;
}

.place-header {
    display: flex;
    gap: 30px;
    margin-bottom: 24px;
}

.place-image-wrapper {
    flex-shrink: 0;
}

.place-image {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.place-info {
    flex-grow: 1;
}

.place-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.place-name {
    font-size: 24px;
    font-weight: bold;
    color: #111827;
    margin: 0 0 4px 0;
    text-align: left;
}

.place-address {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
    text-align: left;
}

.place-time {
    text-align: right;
}

.place-time p {
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
    .event-title-row {
        flex-direction: column;
    }
    
    .event-date-wrapper {
        text-align: left;
        margin-top: 12px;
    }
    
    .place-header {
        flex-direction: column;
    }
    
    .place-details {
        flex-direction: column;
    }
    
    .place-time {
        text-align: left;
        margin-top: 8px;
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