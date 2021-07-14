<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class UrlTest extends TestCase
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
