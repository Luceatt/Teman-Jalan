<?php

namespace Tests\Feature\EventPlanning;

use App\Features\EventPlanning\Models\Activity;
use App\Features\EventPlanning\Models\Rundown;
use App\Features\Location\Models\Place;
use App\Features\Location\Models\PlaceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Rundown $rundown;
    protected Place $place;

    protected function setUp(): void
    {
        parent::setUp();
        PlaceCategory::factory()->create();
        $this->rundown = Rundown::factory()->create();
        $this->place = Place::factory()->create();
    }

    public function test_user_can_add_an_activity_to_a_rundown()
    {
        $activityData = [
            'rundown_id' => $this->rundown->id,
            'place_id' => $this->place->id,
            'name' => 'Test Activity',
            'start_time' => now()->format('Y-m-d H:i:s'),
            'end_time' => now()->addHour()->format('Y-m-d H:i:s'),
        ];

        $response = $this->post(route('activities.store'), $activityData);

        $response->assertRedirect(route('rundowns.show', $this->rundown->id));
        $this->assertDatabaseHas('activities', ['name' => 'Test Activity']);
    }

    public function test_user_can_update_an_activity()
    {
        $activity = Activity::factory()->create(['rundown_id' => $this->rundown->id]);
        $updatedData = [
            'name' => 'Updated Activity Name',
            'start_time' => now()->addHours(2)->format('Y-m-d H:i:s'),
            'end_time' => now()->addHours(3)->format('Y-m-d H:i:s'),
        ];

        $response = $this->put(route('activities.update', $activity->id), $updatedData);

        $response->assertRedirect(route('rundowns.show', $this->rundown->id));
        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'name' => 'Updated Activity Name']);
    }

    public function test_user_can_delete_an_activity()
    {
        $activity = Activity::factory()->create(['rundown_id' => $this->rundown->id]);

        $response = $this->delete(route('activities.destroy', $activity->id));

        $response->assertRedirect(route('rundowns.show', $this->rundown->id));
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }
}