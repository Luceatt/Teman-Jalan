<?php

use App\Features\Location\Models\Place;
use App\Features\Location\Models\PlaceCategory;
use App\Features\Location\Services\PlaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Place Management Feature', function () {

    beforeEach(function () {
        // Create test category
        $this->category = PlaceCategory::factory()->create([
            'name' => 'Restaurant',
            'icon' => '🍽️',
            'color_code' => '#EF4444',
        ]);

        // Mock file storage
        Storage::fake('public');
    });

    it('can create a place with valid data', function () {
        $placeData = [
            'name' => 'Test Restaurant',
            'description' => 'A great place for testing',
            'address' => 'Jakarta Pusat',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'category_id' => $this->category->id,
            'is_active' => true,
        ];

        $image = UploadedFile::fake()->image('restaurant.jpg');

        $placeService = app(PlaceService::class);
        $place = $placeService->createPlace($placeData, $image);

        expect($place)->toBeInstanceOf(Place::class);
        expect($place->name)->toBe($placeData['name']);
        expect($place->image)->toBe('places/' . $image->hashName());

        // Assert image was stored
        Storage::disk('public')->assertExists($place->image);
    });

    it('can retrieve active places', function () {
        // Create test places
        $activePlace = Place::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $inactivePlace = Place::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
        ]);

        $placeService = app(PlaceService::class);
        $places = $placeService->getAllActivePlaces();

        expect($places)->toHaveCount(1);
        expect($places->first()->id)->toBe($activePlace->id);
    });

    it('can search places by name', function () {
        // Create test places
        Place::factory()->create([
            'name' => 'Warung Makan',
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        Place::factory()->create([
            'name' => 'Cafe Kopi',
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $placeService = app(PlaceService::class);
        $results = $placeService->searchPlaces('Warung');

        expect($results->total())->toBe(1);
        expect($results->first()->name)->toBe('Warung Makan');
    });

    it('can find places by category', function () {
        // Create another category
        $cafeCategory = PlaceCategory::factory()->create([
            'name' => 'Cafe',
            'icon' => '☕',
        ]);

        // Create places in different categories
        Place::factory()->create([
            'name' => 'Restaurant A',
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        Place::factory()->create([
            'name' => 'Cafe B',
            'category_id' => $cafeCategory->id,
            'is_active' => true,
        ]);

        $placeService = app(PlaceService::class);
        $places = $placeService->getPlacesByCategory($this->category->id);

        expect($places)->toHaveCount(1);
        expect($places->first()->name)->toBe('Restaurant A');
    });

    it('can update place information', function () {
        $place = Place::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $updatedData = [
            'name' => 'Updated Restaurant Name',
            'description' => 'Updated description',
            'address' => 'Updated Address',
        ];

        $placeService = app(PlaceService::class);
        $updated = $placeService->updatePlace($place->id, $updatedData);

        expect($updated)->toBeTrue();

        $place->refresh();
        expect($place->name)->toBe($updatedData['name']);
        expect($place->description)->toBe($updatedData['description']);
    });

    it('can delete a place and its image', function () {
        $place = Place::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $image = UploadedFile::fake()->image('place.jpg');
        $imagePath = $image->store('places', 'public');

        $place->update(['image' => $imagePath]);

        $placeService = app(PlaceService::class);
        $deleted = $placeService->deletePlace($place->id);

        expect($deleted)->toBeTrue();
        expect(Place::find($place->id))->toBeNull();

        // Assert image was deleted
        Storage::disk('public')->assertMissing($imagePath);
    });

    it('validates place data correctly', function () {
        $placeService = app(PlaceService::class);

        // Test with invalid data
        $invalidData = [
            'name' => '', // Required field empty
            'latitude' => -100, // Invalid latitude
            'longitude' => 200, // Invalid longitude
        ];

        expect(fn() => $placeService->createPlace($invalidData))
            ->toThrow(Exception::class);
    });

    it('can find nearby places within radius', function () {
        // Create places at different locations
        $jakartaPlace = Place::factory()->create([
            'name' => 'Jakarta Place',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $bandungPlace = Place::factory()->create([
            'name' => 'Bandung Place',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $placeService = app(PlaceService::class);

        // Search within 50km of Jakarta
        $nearbyPlaces = $placeService->getNearbyPlaces(-6.2088, 106.8456, 50);

        expect($nearbyPlaces)->toHaveCount(1);
        expect($nearbyPlaces->first()->name)->toBe('Jakarta Place');
    });
});