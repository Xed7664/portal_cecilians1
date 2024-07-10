<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student; // Make sure to import your Student model

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'StudentID' => 'SCC-14-' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'FullName' => $this->faker->unique()->regexify('[A-Z][a-z]+, [A-Z][a-z]+( [A-Z])?( [A-Z])?'),
            'Birthday' => $this->faker->date,
            'Gender' => $this->faker->randomElement(['Male', 'Female']),
            'Address' => $this->faker->address,
            'Status' => $this->faker->randomElement(['Registered', 'Not Registered']),
            'Semester' => $this->faker->randomElement(['Semester 1', 'Semester 2']),
            'YearLevel' => $this->faker->numberBetween(1, 4),
            'Section' => $this->faker->optional(0.2)->word,
            'Major' => $this->faker->optional(0.3)->word,
            'Course' => $this->faker->randomElement(['BSIT', 'BSBA', 'BSED', 'BEED', 'BSTM', 'BSHTM', 'BSCRIM']),
            'Scholarship' => $this->faker->optional(0.1)->word,
            'SchoolYear' => '2023-2024',
        ];
    }
}
