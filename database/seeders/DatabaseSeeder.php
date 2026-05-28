<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pusat = Branch::create([
            'name' => 'Pusat',
            'code' => 'PST',
            'address' => 'Jl. Raya Utama No. 1',
            'phone' => '021-5550001',
            'email' => 'pusat@pos.test',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@pos.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'branch_id' => $pusat->id,
        ]);

        User::factory()->create([
            'name' => 'Kasir',
            'email' => 'kasir@pos.test',
            'password' => bcrypt('password'),
            'role' => 'kasir',
            'branch_id' => $pusat->id,
        ]);

        User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@pos.test',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'branch_id' => $pusat->id,
        ]);
    }
}
