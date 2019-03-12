<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Record;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);

        factory(User::class, 4)
            ->create()
            ->each(function ($user) {
                factory(Category::class, 5)->create([
                    'user_id' => $user->id,
            ])->each(function ($category) use ($user) {
                factory(Record::class, rand(0, 3))
                    ->create([
                        'user_id' => $user,
                        'category_id' => $category->id,
                    ]);
            });
        });

        // Test users 1
        $user1 = create(User::class, [
            'name' => 'Arturas',
            'email' => 'arturas@mail.com',
            'password' => bcrypt('12345678'),
        ]);

        create(Category::class, [
            'user_id' => $user1->id
        ], 2)->each(function ($category) use ($user1) {
            create(Record::class, [
                'user_id' => $user1->id,
                'category_id' => $category->id,
            ], 3);
        });

        // Test users 2
        $user2 = create(User::class, [
            'name' => 'Paulina',
            'email' => 'paulina@mail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
