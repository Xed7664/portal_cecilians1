<?php

namespace Tests\Feature\Livewire\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SystemSetting;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationFormTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_register()
    {
        // Enable registration in the system settings for the test
        SystemSetting::setRegistrationEnabled(true);

        Livewire::test('auth.registration-form')
            ->set('school_id', '12345')
            ->set('birthdate', '1990-01-01')
            ->set('username', 'testuser')
            ->set('email', 'test@example.com')
            ->set('password', 'Test1234!')
            ->set('terms_agreed', true)
            ->call('register')
            ->assertOk(); // Adjust this based on your expected behavior
    }
}
