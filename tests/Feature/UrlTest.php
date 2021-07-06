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

    public function testCreate(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    public function testStoreUrl(): void
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post('urls', $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $data['url']);
    }

    public function testStoreExistingUrl(): void
    {
        $data = ['url' => ['name' => 'http://google.com']];
        $response = $this->post('urls', $data);
        $countBefore = DB::table('urls')->count();
        $response = $this->post('urls', $data);
        $countAfter = DB::table('urls')->count();

        $this->assertEquals($countBefore, $countAfter);
        $response->assertRedirect();
    }

    public function testShow(): void
    {
        $response = $this->get('/urls/1');
        $response->assertOk();
    }

    public function testIndex(): void
    {
        $response = $this->get('/urls');
        $response->assertOk();
    }

    public function testStoreChecks(): void
    {
        $data = [
            'h1' => "Do not expect a miracle, miracles yourself!",
            'keywords' => "test wow miracle",
            'description' => "statements of great people",
        ];

        $pageHtml = file_get_contents("tests/fixtures/index.html") ?? "";

        HTTP::fake(['*' => Http::response((string) $pageHtml, 200)]);
        $response = $this->post('/urls/1/checks');
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('url_checks', $data);
    }
}
