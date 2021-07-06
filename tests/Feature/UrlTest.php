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
            'created_at' => $faker->dateTime($max = 'now', $timezone = null),
            'updated_at' => $faker->dateTime($max = 'now', $timezone = null),
        ]);
    }

    public function testCreate()
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    public function testStoreUrl()
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post('urls', $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $data['url']);
    }

    public function testStoreExistingUrl()
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post('urls', $data);
        $countBefore = DB::table('urls')->count();
        $response = $this->post('urls', $data);
        $countAfter = DB::table('urls')->count();

        $this->assertSame($countBefore, $countAfter);
        $response->assertRedirect();
    }

    public function testShow()
    {
        $response = $this->get('/urls/1');
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get('/urls');
        $response->assertOk();
    }

    public function testStoreChecks()
    {
        $headers = [
            'h1' => 'h1',
            'keywords' => 'keywords',
            'description' => 'description',
        ];

        HTTP::fake(['*' => Http::response('test', 200, $headers)]);
        $response = $this->post('/urls/1/checks');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('url_checks', $headers);
    }
}
