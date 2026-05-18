<?php

namespace Tests\Feature;

use App\Mail\SignupVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SignupVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_sends_verification_code_before_creating_user(): void
    {
        Mail::fake();

        $response = $this->post(route('signup.submit'), $this->signupPayload());

        $response->assertRedirect(route('signup.verify'));
        $response->assertSessionHas('signup_verification');
        $response->assertSessionMissing('authenticated');

        $this->assertDatabaseMissing('users', [
            'email' => 'new-user@example.com',
        ]);

        $this->get(route('signup.verify'))
            ->assertOk()
            ->assertSee('Verify signup')
            ->assertSee('new-user@example.com');

        Mail::assertSent(SignupVerificationCode::class, function (SignupVerificationCode $mail) {
            return $mail->hasTo('new-user@example.com')
                && preg_match('/^\d{6}$/', $mail->code) === 1;
        });
    }

    public function test_signup_verification_code_creates_user_and_signs_them_in(): void
    {
        Mail::fake();

        $sentCode = null;

        $this->post(route('signup.submit'), $this->signupPayload());

        Mail::assertSent(SignupVerificationCode::class, function (SignupVerificationCode $mail) use (&$sentCode) {
            $sentCode = $mail->code;

            return true;
        });

        $response = $this->post(route('signup.verify.submit'), [
            'code' => $sentCode,
        ]);

        $response->assertRedirect('/explore');
        $response->assertSessionHas('authenticated', true);
        $response->assertSessionMissing('signup_verification');

        $user = User::where('email', 'new-user@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('New User', $user->name);
        $this->assertTrue(Hash::check('new-secret', $user->password));
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame($user->id, session('user_id'));
    }

    public function test_wrong_signup_verification_code_does_not_create_user(): void
    {
        Mail::fake();

        $this->post(route('signup.submit'), $this->signupPayload());

        $response = $this->post(route('signup.verify.submit'), [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        $response->assertSessionMissing('authenticated');

        $this->assertDatabaseMissing('users', [
            'email' => 'new-user@example.com',
        ]);
    }

    private function signupPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'new-secret',
        ], $overrides);
    }
}
