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

        factory(User::class, 5)
            ->create()
            ->each(function ($user) {
                factory(Category::class, 5)->create([
                    'user_id' => $user->id,
            ])->each(function ($category) use ($user) {
                factory(Record::class, rand(0, 3))->create([
                    'user_id' => $user,
                    'category_id' => $category->id,
                ]);
            });
        });
    }
}
