<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'wali kelas']);

        // Assign roles to existing users
        $gurus = Guru::all();
        foreach ($gurus as $guru) {
            $guru->assignRole($guru->role);
        }
    }
}
