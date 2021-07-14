<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class UrlCheckTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $faker = \Faker\Factory::create();
        DB::table('urls')->insertGetId([
            'name' => $faker->url,
            'id' => 1,
        ]);
    }

    public function testStoreUrlChecks(): void
    {
        $data = [
            'id' => 1,
            'url_id' => 1,
            'status_code' => 200,
            'h1' => "Do not expect a miracle, miracles yourself!",
            'keywords' => "test wow miracle",
            'description' => "statements of great people",
        ];

        $pageHtml = @file_get_contents("tests/fixtures/index.html");

        if ($pageHtml === false) {
            throw new Exception("Error opening a file with a fixture");
        }

        HTTP::fake(['*' => Http::response($pageHtml, 200)]);
        $response = $this->post(route('urls.checks.store', ['url' => 1]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $data);
    }
}
