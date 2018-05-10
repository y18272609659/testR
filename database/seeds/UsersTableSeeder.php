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
            ->create();
//            ->each(
//                function ($u) {
//                    $u->config()->save(
//                        factory(App\Config::class)->make()
//                    );
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
//
//                    foreach (range(1, 4) as $value) {
//                        $u->accountCategory()->save(
//                            factory(App\AccountCategory::class)->make()
//                        );
//                    }
//                    foreach (range(1, 15) as $value) {
//                        $u->accountBill()->save(
//                            factory(App\AccountBill::class)->make()
//                        );
//                    }
//                    foreach (range(1, 4) as $value) {
//                        $u->accountStatistic()->save(
//                            factory(App\AccountStatistic::class)->make()
//                        );
//                    }
//                }
//            );
    }
}
