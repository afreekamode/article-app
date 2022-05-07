<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Article::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 20; $i++) {
            Article::create([
                'subject' => $faker->sentence,
                'body' => $faker->text($maxNbChars = 200),
                'media' => $faker->imageUrl($width = 640, $height = 480)
            ]);
        }
    }
}
