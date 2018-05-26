<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $_COOKIE['id'] = 0;

        factory(App\User::class, 25)
            ->create()
            ->each(
                function ($u) {
                    $_COOKIE['id'] = $u->id;

                    $u->resource()->save(
                        factory(App\Models\Resource::class)->make()
                    );

                    $u->building()->save(
                        factory(App\Models\Building::class)->make()
                    );
//
//                    foreach (range(1, 10) as $value) {
//                        $u->mission()->save(
//                            factory(App\Mission::class)->make()
//                        );
//                    }
//                    foreach (range(1, 4) as $value) {
//                        $u->tomatoStatistic()->save(
//                            factory(App\TomatoStatistic::class)->make()
//                        );
//                    }
                }
            );
    }
}
