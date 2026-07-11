<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           RolesAndPermissionsSeeder::class,
           CategorySeeder::class,
           MarketTypeSeeder::class,
           OutcomeTypeSeeder::class,
           MarketTemplateSeeder::class,
           ResultTypeSeeder::class,
           TaxonomySeeder::class,
        ]);

        $admin = User::firstOrCreate(
             ['email' => 'admin@example.com'],
             [
                 'name' => 'Admin',
                 'password' => bcrypt('password'),
             ]
        );

        if (! $admin->hasRole('admin')) {
             $admin->assignRole('admin');
        }
    }
}
