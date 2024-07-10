<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Student;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     return [
    //         'name' => fake()->name(),
    //         'email' => fake()->unique()->safeEmail(),
    //         'email_verified_at' => now(),
    //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    //         'remember_token' => Str::random(10),
    //     ];
    // }
    public function definition(): array
    {
        $student = Student::inRandomOrder()->first();

        return [
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // You might want to use a more secure method in a real scenario
            'type' => 'student',
            'student_id' => $student ? $student->StudentID : null,
            'employee_id' => null,
            'avatar' => 'default-profile.png',
            'bio' => $this->faker->sentence,
            'google_id' => null,
            'facebook_link' => null,
            'twitter_link' => null,
            'instagram_link' => null,
            'youtube_link' => null,
            'tiktok_link' => null,
            'status' => $this->faker->randomElement(['unverified', 'member']),
            'device_token' => $this->faker->uuid,
            'remember_token' => Str::random(10),
            'email_verified_at' => $this->faker->randomElement([now(), null]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
