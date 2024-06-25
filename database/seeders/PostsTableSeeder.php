<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 10; $i++) {
            $newPost = new Post();
            $newPost->title = $faker->sentence(3);
            $newPost->content = $faker->text(500);
            //slug è il metodo della classe str che trasforma la stringa data in uno slug
            $newPost->slug = Str::slug($newPost->title);
            $newPost->save();
        }
    }
}
