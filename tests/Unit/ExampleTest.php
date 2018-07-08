<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function Test()
    {
        $key = getUserKey('buildList');
//        $ = json_decode(Redis::get($key), true);
        $this->assertTrue(true);
    }
}
