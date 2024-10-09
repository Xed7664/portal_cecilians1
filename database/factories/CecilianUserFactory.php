<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CecilianUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $student = Student::inRandomOrder()->first();

        if ($type === 'student') {
            $student = Student::inRandomOrder()->first();
        } else {
            $employee = Employee::inRandomOrder()->first();
        }

        return [
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // You might want to use a more secure method in a real scenario
            'type' => $type,
            'student_id' => $student ? $student->StudentID : null,
            'employee_id' => $employee ? $employee->EmployeeID : null,
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
}
