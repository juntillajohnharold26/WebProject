<?php

declare(strict_types=1);

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DevSellVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_devsell_join_submits_for_admin_review_and_does_not_require_verification_code(): void
    {
        $user = User::factory()->create([
            'email' => 'seller@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('seller-secret'),
        ]);

        session(['authenticated' => true, 'user_id' => $user->id]);

        $response = $this->post(route('devsell.join.submit'), $this->sellerPayload([
            'email' => $user->email,
            'password' => 'seller-secret',
        ]));


        $response->assertRedirect(route('devsell.join'));
        $response->assertSessionHas('success');

        $this->get(route('devsell.join'))
            ->assertOk()
            ->assertSee('Join DevSell')
            ->assertDontSee('Enter verification code')
            ->assertDontSee('Verification Code');

        $user->refresh();

        $this->assertFalse($user->devsell_active);
        $this->assertSame('pending', $user->devsell_status);
        $this->assertSame('Pixel Forge', $user->devsell_display_name);
        $this->assertSame('Pixel Forge Store', $user->devsell_store_name);
    }

    public function test_devsell_join_verify_route_does_not_exist(): void
    {
        try {
            route('devsell.join.verify');
            $this->fail('Expected route [devsell.join.verify] to not exist.');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('devsell.join.verify', $e->getMessage());
        }
    }



    private function sellerPayload(array $overrides = []): array
    {
        return array_merge([
            'display_name' => 'Pixel Forge',
            'email' => 'seller@example.com',
            'password' => 'seller-secret',
            'store_name' => 'Pixel Forge Store',
            'specialty' => 'UI Kits',
            'portfolio' => 'https://example.com/portfolio',
            'bio' => 'Polished interface kits for product teams.',
        ], $overrides);
    }
}

