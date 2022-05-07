<?php

namespace Database\Seeders;

use App\Models\Tag;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Database\Seeder;

class ArticlesTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 40; $i++) {
            Tag::create([
                'tag' => $faker->realText(rand(10,20)),
                'post_id' => $faker->randomDigitNot(5),
            ]);
        }
    }
}
