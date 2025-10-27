<?php

namespace Database\Seeders;

use App\Models\EventPlanning\Activity;
use App\Models\EventPlanning\Rundown;
use App\Models\Location\Place;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RundownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some places to associate with activities
        $places = Place::inRandomOrder()->limit(10)->get();

        if ($places->count() < 2) {
            $this->command->info('Not enough places to create a rundown with activities. Please seed places first.');
            return;
        }

        // Create a sample rundown for today
        $rundownToday = Rundown::create([
            'title' => 'Petualangan Kuliner Jakarta',
            'description' => 'Menjelajahi berbagai tempat kuliner ikonik di Jakarta dalam satu hari.',
            'date' => Carbon::today(),
            'status' => 'published',
            'is_public' => true,
            'created_by' => 'Admin',
        ]);

        Activity::create([
            'rundown_id' => $rundownToday->id,
            'place_id' => $places[0]->id,
            'name' => 'Sarapan di ' . $places[0]->name,
            'description' => 'Memulai hari dengan sarapan khas.',
            'start_time' => Carbon::today()->setTime(8, 0),
            'end_time' => Carbon::today()->setTime(9, 30),
            'order' => 1,
        ]);

        Activity::create([
            'rundown_id' => $rundownToday->id,
            'place_id' => $places[1]->id,
            'name' => 'Makan Siang di ' . $places[1]->name,
            'description' => 'Mencoba hidangan utama yang lezat.',
            'start_time' => Carbon::today()->setTime(12, 0),
            'end_time' => Carbon::today()->setTime(13, 30),
            'order' => 2,
        ]);

        // Create a sample rundown for tomorrow
        $rundownTomorrow = Rundown::create([
            'title' => 'Wisata Sejarah dan Budaya',
            'description' => 'Mengunjungi museum dan tempat bersejarah di sekitar kota.',
            'date' => Carbon::tomorrow(),
            'status' => 'draft',
            'is_public' => false,
            'created_by' => 'Admin',
        ]);

        if ($places->count() > 3) {
            Activity::create([
                'rundown_id' => $rundownTomorrow->id,
                'place_id' => $places[2]->id,
                'name' => 'Kunjungan Pagi di ' . $places[2]->name,
                'start_time' => Carbon::tomorrow()->setTime(10, 0),
                'end_time' => Carbon::tomorrow()->setTime(12, 0),
                'order' => 1,
            ]);

            Activity::create([
                'rundown_id' => $rundownTomorrow->id,
                'place_id' => $places[3]->id,
                'name' => 'Eksplorasi Siang di ' . $places[3]->name,
                'start_time' => Carbon::tomorrow()->setTime(13, 0),
                'end_time' => Carbon::tomorrow()->setTime(15, 0),
                'order' => 2,
            ]);
        }

        $this->command->info('Rundown and activity seeders have been run successfully!');
    }
}
