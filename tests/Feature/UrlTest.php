<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Url;
use Carbon\Carbon;

class UrlTest extends TestCase
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

    public function testStoreUrl(): void
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post(route('urls.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $data['url']);
    }

    public function testStoreExistingUrl(): void
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post(route('urls.store'), $data);
        $countBefore = DB::table('urls')->count();
        $response = $this->post('urls', $data);
        $countAfter = DB::table('urls')->count();

        self::assertEquals($countBefore, $countAfter);
        $response->assertRedirect();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', ['url' => 1]));
        $response->assertOk();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }
}
