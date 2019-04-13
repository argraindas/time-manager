<?php

use App\Category;
use App\Record;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->categories()->records();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Seed the categories table.
     */
    protected function categories()
    {
        Category::truncate();

        User::all()->each(function ($user) {
            create(Category::class, [
                'user_id' => $user->id,
            ], 3);
        });

        return $this;
    }

    /**
     * Seed the category-related tables.
     */
    protected function records()
    {
        Record::truncate();

        Category::all()->each(function ($category) {
            create(Record::class, [
                'category_id' => $category->id,
                'user_id' => $category->user_id,
            ], 3);
        });
    }
}
