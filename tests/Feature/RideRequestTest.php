<?php

namespace Tests\Feature;

use App\Models\RideRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RideRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_post_ride_request_via_api(): void
    {
        $user = User::factory()->create(['name' => 'Sarah Chen']);
        Sanctum::actingAs($user);

        $payload = [
            'from_location'  => 'Chattogram',
            'to_location'    => 'Cox\'s Bazar',
            'travel_date'    => '2026-09-20',
            'preferred_time' => 'Morning',
            'note'           => 'Looking for a seat. I can share fuel cost.',
            'seats_needed'   => 1,
        ];

        $response = $this->postJson('/api/v1/ride-requests', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'from_location'         => 'Chattogram',
                    'to_location'           => 'Cox\'s Bazar',
                    'travel_date'           => '2026-09-20',
                    'travel_date_formatted' => 'Sunday, 20 September 2026',
                    'preferred_time'        => 'Morning',
                    'note'                  => 'Looking for a seat. I can share fuel cost.',
                    'seats_needed'          => 1,
                    'seats_text'            => '1 Seat',
                    'status'                => 'active',
                    'user'                  => [
                        'id'   => $user->id,
                        'name' => 'Sarah Chen',
                    ],
                ]
            ]);

        $this->assertDatabaseHas('ride_requests', [
            'user_id'       => $user->id,
            'from_location' => 'Chattogram',
            'to_location'   => 'Cox\'s Bazar',
            'seats_needed'  => 1,
        ]);
    }

    public function test_ride_request_fails_if_note_exceeds_300_characters(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'from_location'  => 'Dhaka',
            'to_location'    => 'Sylhet',
            'travel_date'    => '2026-09-20',
            'note'           => str_repeat('a', 301),
        ];

        $response = $this->postJson('/api/v1/ride-requests', $payload);
        $response->assertStatus(422);
    }

    public function test_user_can_view_my_ride_requests_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        RideRequest::create([
            'user_id'        => $user->id,
            'from_location'  => 'Dhaka',
            'to_location'    => 'Chittagong',
            'travel_date'    => '2026-09-25',
            'preferred_time' => 'Anytime',
            'seats_needed'   => 2,
            'status'         => 'active',
        ]);

        $response = $this->getJson('/api/v1/my-ride-requests');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('data.data.0.seats_needed', 2)
            ->assertJsonPath('data.data.0.seats_text', '2 Seats');
    }

    public function test_public_can_browse_and_filter_ride_requests_via_api(): void
    {
        $user = User::factory()->create(['name' => 'Sarah Chen']);

        // Request 1: Chattogram -> Cox's Bazar, Morning, 1 seat
        RideRequest::create([
            'user_id'        => $user->id,
            'from_location'  => 'Chattogram',
            'to_location'    => 'Cox\'s Bazar',
            'travel_date'    => Carbon::today()->addDays(2)->toDateString(),
            'preferred_time' => 'Morning',
            'note'           => 'Looking for a seat.',
            'seats_needed'   => 1,
            'status'         => 'active',
        ]);

        // Request 2: Dhaka -> Sylhet, Evening, 2 seats
        RideRequest::create([
            'user_id'        => $user->id,
            'from_location'  => 'Dhaka',
            'to_location'    => 'Sylhet',
            'travel_date'    => Carbon::today()->addDays(10)->toDateString(),
            'preferred_time' => 'Evening',
            'note'           => 'Family travel.',
            'seats_needed'   => 2,
            'status'         => 'active',
        ]);

        // 1. All requests
        $allResponse = $this->getJson('/api/v1/ride-requests');
        $allResponse->assertStatus(200)
            ->assertJsonPath('data.total', 2);

        // 2. Filter by from location
        $fromResponse = $this->getJson('/api/v1/ride-requests?from=Chattogram');
        $fromResponse->assertStatus(200)
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.data.0.from_location', 'Chattogram');

        // 3. Filter by seats needed
        $seatResponse = $this->getJson('/api/v1/ride-requests?seats_needed=2');
        $seatResponse->assertStatus(200)
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.data.0.from_location', 'Dhaka');

        // 4. Quick Tab: Next 7 Days
        $next7DaysResponse = $this->getJson('/api/v1/ride-requests?tab=next_7_days');
        $next7DaysResponse->assertStatus(200)
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.data.0.from_location', 'Chattogram');

        // 5. Sorting test: date_asc
        $sortResponse = $this->getJson('/api/v1/ride-requests?sort_by=date_asc');
        $sortResponse->assertStatus(200)
            ->assertJsonPath('data.data.0.from_location', 'Chattogram')
            ->assertJsonPath('data.data.1.from_location', 'Dhaka');
    }

    public function test_user_can_update_and_delete_own_ride_request_via_api(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $rideRequest = RideRequest::create([
            'user_id'        => $user->id,
            'from_location'  => 'Dhaka',
            'to_location'    => 'Khulna',
            'travel_date'    => '2026-09-25',
            'preferred_time' => 'Anytime',
            'seats_needed'   => 1,
            'status'         => 'active',
        ]);

        // Update own
        $updateResponse = $this->putJson('/api/v1/ride-requests/' . $rideRequest->id, [
            'from_location' => 'Dhaka Airport',
            'seats_needed'  => 3,
        ]);
        $updateResponse->assertStatus(200);
        $this->assertEquals('Dhaka Airport', $rideRequest->fresh()->from_location);
        $this->assertEquals(3, $rideRequest->fresh()->seats_needed);

        // Forbidden for other user
        Sanctum::actingAs($otherUser);
        $forbiddenResponse = $this->putJson('/api/v1/ride-requests/' . $rideRequest->id, [
            'from_location' => 'Another City',
        ]);
        $forbiddenResponse->assertStatus(403);

        // Delete own
        Sanctum::actingAs($user);
        $deleteResponse = $this->deleteJson('/api/v1/ride-requests/' . $rideRequest->id);
        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('ride_requests', ['id' => $rideRequest->id]);
    }

    public function test_admin_can_manage_ride_requests(): void
    {
        $this->withoutMiddleware([
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        // 1. Admin accesses index
        $indexResponse = $this->actingAs($admin, 'sanctum')->get('/admin/ride-requests');
        $indexResponse->assertStatus(200);

        // 2. Admin accesses create page
        $createPageResponse = $this->actingAs($admin, 'sanctum')->get('/admin/ride-requests/create');
        $createPageResponse->assertStatus(200);

        // 3. Admin creates ride request via web form
        $response = $this->actingAs($admin, 'sanctum')->post('/admin/ride-requests/store', [
            'user_id'        => $user->id,
            'from_location'  => 'Rajshahi',
            'to_location'    => 'Dhaka',
            'travel_date'    => '2026-10-01',
            'preferred_time' => 'Afternoon (12:00 PM - 5:00 PM)',
            'seats_needed'   => 2,
            'note'           => 'Admin created request',
            'status'         => 'pending',
        ]);

        $response->assertRedirect('/admin/ride-requests');

        $rideRequest = RideRequest::where('from_location', 'Rajshahi')->first();
        $this->assertNotNull($rideRequest);
        $this->assertEquals(2, $rideRequest->seats_needed);

        // 4. Admin accesses edit page
        $editPageResponse = $this->actingAs($admin, 'sanctum')->get('/admin/ride-requests/edit/' . $rideRequest->id);
        $editPageResponse->assertStatus(200);

        // 5. Admin edits ride request
        $updateResponse = $this->actingAs($admin, 'sanctum')->put('/admin/ride-requests/update/' . $rideRequest->id, [
            'user_id'        => $user->id,
            'from_location'  => 'Rajshahi City',
            'to_location'    => 'Dhaka North',
            'travel_date'    => '2026-10-02',
            'preferred_time' => 'Anytime',
            'seats_needed'   => 3,
            'note'           => 'Updated note',
            'status'         => 'active',
        ]);

        $updateResponse->assertRedirect('/admin/ride-requests');
        $this->assertEquals('Rajshahi City', $rideRequest->fresh()->from_location);
        $this->assertEquals(3, $rideRequest->fresh()->seats_needed);

        // 6. Admin DataTables AJAX request
        $ajaxResponse = $this->actingAs($admin, 'sanctum')->getJson('/admin/ride-requests', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $ajaxResponse->assertStatus(200)->assertJsonStructure(['data']);

        // 7. Admin deletes ride request
        $deleteResponse = $this->actingAs($admin, 'sanctum')->delete('/admin/ride-requests/delete/' . $rideRequest->id);
        $deleteResponse->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('ride_requests', ['id' => $rideRequest->id]);
    }
}
