<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        create(User::class, [], 4);

        collect([
            [
                'name' => 'Arturas',
                'email' => 'arturas@mail.com',
                'password' => '123',
            ],
            [
                'name' => 'Paulina',
                'email' => 'paulina@mail.com',
                'password' => '123',
            ],
        ])->each(function ($user) {
            create(User::class, [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
            ]);
        });
    }
}
