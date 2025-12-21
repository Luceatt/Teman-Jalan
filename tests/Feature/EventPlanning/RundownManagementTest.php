<?php

namespace Tests\Feature\EventPlanning;

use App\Models\EventPlanning\Rundown;
use App\Models\Location\Place;
use App\Models\Location\PlaceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RundownManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        PlaceCategory::factory()->create();
        Place::factory(10)->create();
    }

    public function test_user_can_view_rundown_index_page()
    {
        Rundown::factory(5)->create();

        $response = $this->get(route('rundowns.index'));

        $response->assertStatus(200);
        $response->assertViewIs('rundowns.index');
        $response->assertSee('Rundown Management');
    }

    public function test_user_can_create_a_new_rundown()
    {
        $rundownData = [
            'title' => 'My Awesome Rundown',
            'description' => 'This is a test rundown.',
            'date' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('rundowns.store'), $rundownData);

        $response->assertRedirect();
        $this->assertDatabaseHas('rundowns', ['title' => 'My Awesome Rundown']);
    }

    public function test_user_can_view_a_rundown_details()
    {
        $rundown = Rundown::factory()->create();

        $response = $this->get(route('rundowns.show', $rundown->id));

        $response->assertStatus(200);
        $response->assertViewIs('rundowns.show');
        $response->assertSee($rundown->title);
    }

    public function test_user_can_update_a_rundown()
    {
        $rundown = Rundown::factory()->create();
        $updatedData = [
            'title' => 'Updated Rundown Title',
            'description' => 'Updated description.',
            'date' => now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->put(route('rundowns.update', $rundown->id), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('rundowns', ['id' => $rundown->id, 'title' => 'Updated Rundown Title']);
    }

    public function test_user_can_delete_a_rundown()
    {
        $rundown = Rundown::factory()->create();

        $response = $this->delete(route('rundowns.destroy', $rundown->id));

        $response->assertRedirect(route('rundowns.index'));
        $this->assertDatabaseMissing('rundowns', ['id' => $rundown->id]);
    }
}