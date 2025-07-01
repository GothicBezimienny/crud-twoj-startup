<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Email;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            User::factory()
                ->count(5)
                ->create()
                ->each(function (User $user) {
                    Email::factory()
                        ->count(rand(2, 3))
                        ->create(['user_id' => $user->id]);
                });
        });
    }
}
