<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Url;
use Carbon\Carbon;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $now = Carbon::now('Europe/Moscow');
        $faker = \Faker\Factory::create();
        DB::table('urls')->insert([
            'name' => $faker->url,
        ]);
    }

    public function testHome(): void
    {
        $response = $this->get(route('urls.create'));
        $response->assertOk();
    }
}
