@extends('layouts.app')

@section('title', 'History')

@section('content')
<h1 class="page-title">History</h1>

<div class="container">

    {{-- Baris tombol toggle --}}
    <div class="toggle-container">
        <button id="btnEvents" class="toggle-tab active">Events</button>
        <button id="btnPlaces" class="toggle-tab">Places</button>
    </div>

    {{-- Area konten --}}
    <div class="content-area">
        <div id="eventsContent">
            @include('history.partials.events-content')
        </div>

        <div id="placesContent" style="display: none;">
            @include('history.partials.places-content')
        </div>
    </div>
</div>

{{-- Script toggle --}}
<script>
    const btnEvents = document.getElementById('btnEvents');
    const btnPlaces = document.getElementById('btnPlaces');
    const eventsContent = document.getElementById('eventsContent');
    const placesContent = document.getElementById('placesContent');

    btnEvents.addEventListener('click', () => {
        btnEvents.classList.add('active');
        btnPlaces.classList.remove('active');
        eventsContent.style.display = 'block';
        placesContent.style.display = 'none';
    });

    btnPlaces.addEventListener('click', () => {
        btnPlaces.classList.add('active');
        btnEvents.classList.remove('active');
        eventsContent.style.display = 'none';
        placesContent.style.display = 'block';
    });
</script>

{{-- Styling --}}
<style>
    .page-title {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin: 20px 0;
        background: #f9f9f9;
    }

    .container {
        width: 80%;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding-bottom: 40px;
    }

    .toggle-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        border-bottom: 2px solid #ccc;
    }

    .toggle-tab {
        width: 50%;
        padding: 20px 0;
        background-color: #e0e0e0;
        border: none;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .toggle-tab.active {
        background-color: #3498db;
        color: white;
    }

    .toggle-tab:hover {
        background-color: #bdbdbd;
    }

    .content-area {
        padding: 30px 20px;
        text-align: center;
    }
</style>
@endsection
