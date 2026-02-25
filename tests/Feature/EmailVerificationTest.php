<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create necessary roles
        Role::create(['name' => 'perusahaan', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_email_verification_screen_can_be_rendered()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('perusahaan');
        
        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('pages.verify-email');
    }

    public function test_email_can_be_verified_and_redirects_to_wait_page_if_not_validated()
    {
        $user = User::factory()->unverified()->create([
            'is_validated' => false,
        ]);
        $user->assignRole('perusahaan');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // Since it's redirected to perusahaan.dashboard.index which is protected by 'validated'
        // it should eventually end up at verification.wait
        $response->assertRedirect(route('perusahaan.dashboard.index'));
        
        $response = $this->actingAs($user->fresh())->get(route('perusahaan.dashboard.index'));
        $response->assertRedirect(route('verification.wait'));
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('perusahaan');

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_unverified_user_cannot_access_dashboard()
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('perusahaan');

        $response = $this->actingAs($user)->get(route('perusahaan.dashboard.index'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_but_unvalidated_user_cannot_access_dashboard()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_validated' => false,
        ]);
        $user->assignRole('perusahaan');

        $response = $this->actingAs($user)->get(route('perusahaan.dashboard.index'));

        $response->assertRedirect(route('verification.wait'));
    }

    public function test_verified_and_validated_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_validated' => true,
        ]);
        $user->assignRole('perusahaan');

        $response = $this->actingAs($user)->get(route('perusahaan.dashboard.index'));

        $response->assertStatus(200);
    }
}
