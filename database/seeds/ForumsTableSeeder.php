<?php

use Illuminate\Database\Seeder;

class ForumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Forum::class, 5)->create(['user_id' => 1]);
        factory(App\Forum::class, 5)->create(['user_id' => 2]);
        factory(App\Forum::class, 5)->create(['user_id' => 3]);
        factory(App\Forum::class, 5)->create(['user_id' => 4]);
        factory(App\Forum::class, 5)->create(['user_id' => 5]);
    }
}
