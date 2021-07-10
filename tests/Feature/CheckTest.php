<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Url;
use Carbon\Carbon;

class CheckTest extends TestCase
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

    public function testStoreChecks(): void
    {
        $data = [
            'h1' => "Do not expect a miracle, miracles yourself!",
            'keywords' => "test wow miracle",
            'description' => "statements of great people",
        ];

        $pageHtml = file_get_contents("tests/fixtures/index.html") ?? "";

        HTTP::fake(['*' => Http::response((string) $pageHtml, 200)]);
        $response = $this->post(route('urls.checks.store', ['url' => 1]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('url_checks', $data);
    }
}
