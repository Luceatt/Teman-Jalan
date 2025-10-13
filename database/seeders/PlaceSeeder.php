<?php

namespace Database\Seeders;

use App\Features\Location\Models\Place;
use App\Features\Location\Models\PlaceCategory;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create place categories
        $categories = [
            [
                'name' => 'Restaurant',
                'icon' => '🍽️',
                'color_code' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Cafe',
                'icon' => '☕',
                'color_code' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment',
                'icon' => '🎭',
                'color_code' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Shopping',
                'icon' => '🛍️',
                'color_code' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Outdoor',
                'icon' => '🏞️',
                'color_code' => '#3B82F6',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            PlaceCategory::create($category);
        }

        // Create sample places in Jakarta area
        $places = [
            [
                'name' => 'Warung Nasi Gudeg',
                'description' => 'Traditional Javanese restaurant serving authentic gudeg and other Indonesian dishes.',
                'address' => 'Jl. Gajah Mada No. 123, Jakarta Pusat',
                'latitude' => -6.1378,
                'longitude' => 106.8131,
                'category_id' => 1, // Restaurant
                'is_active' => true,
            ],
            [
                'name' => 'Kopi Janji Jiwa',
                'description' => 'Popular coffee shop chain offering various coffee blends and light snacks.',
                'address' => 'Jl. Thamrin No. 10, Jakarta Pusat',
                'latitude' => -6.1862,
                'longitude' => 106.8229,
                'category_id' => 2, // Cafe
                'is_active' => true,
            ],
            [
                'name' => 'Blok M Square',
                'description' => 'Modern shopping mall with various retail stores and entertainment options.',
                'address' => 'Jl. Bulungan No. 76, Jakarta Selatan',
                'latitude' => -6.2447,
                'longitude' => 106.7998,
                'category_id' => 4, // Shopping
                'is_active' => true,
            ],
            [
                'name' => 'Taman Suropati',
                'description' => 'Beautiful city park perfect for outdoor activities and relaxation.',
                'address' => 'Jl. Teuku Umar No. 1, Jakarta Pusat',
                'latitude' => -6.2019,
                'longitude' => 106.8306,
                'category_id' => 5, // Outdoor
                'is_active' => true,
            ],
            [
                'name' => 'CGV Cinemas Grand Indonesia',
                'description' => 'Modern cinema complex with multiple screens and premium seating.',
                'address' => 'Grand Indonesia Mall, Jakarta Pusat',
                'latitude' => -6.1958,
                'longitude' => 106.8211,
                'category_id' => 3, // Entertainment
                'is_active' => true,
            ],
            [
                'name' => 'Sate Khas Senayan',
                'description' => 'Famous satay restaurant serving various grilled meat skewers with peanut sauce.',
                'address' => 'Jl. Senopati No. 56, Jakarta Selatan',
                'latitude' => -6.2288,
                'longitude' => 106.8081,
                'category_id' => 1, // Restaurant
                'is_active' => true,
            ],
            [
                'name' => 'Anomali Coffee',
                'description' => 'Specialty coffee shop with artisanal brews and cozy atmosphere.',
                'address' => 'Jl. Kemang Raya No. 72, Jakarta Selatan',
                'latitude' => -6.2608,
                'longitude' => 106.8139,
                'category_id' => 2, // Cafe
                'is_active' => true,
            ],
            [
                'name' => 'Plaza Indonesia',
                'description' => 'Luxury shopping mall featuring international brands and fine dining.',
                'address' => 'Jl. M.H. Thamrin No. 28-30, Jakarta Pusat',
                'latitude' => -6.1869,
                'longitude' => 106.8214,
                'category_id' => 4, // Shopping
                'is_active' => true,
            ],
        ];

        foreach ($places as $place) {
            Place::create($place);
        }
    }
}