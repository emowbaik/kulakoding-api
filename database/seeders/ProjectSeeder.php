<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 20; $i++) { 
            Project::create([
                "user_id" => 1,
                "tool_id" => 1,
                "nama_project" => $faker->name(),
                "deskripsi" => $faker->paragraph(),
            ]);
        }
    }
}
