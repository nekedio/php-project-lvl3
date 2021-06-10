<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testPages()
    {
        $responseHomePage = $this->get('/');
        $responseHomePage->assertStatus(200);

        $responseUrlsPage = $this->get('/urls');
        $responseUrlsPage->assertStatus(200);

        $responsePost = $this->post('/add', ['url' => ['name' => 'http://test.com']]);
        $responsePost->assertStatus(302);

        $responseUrlsIdPage = $this->get('/urls/1');
        $responseUrlsIdPage->assertStatus(200);

        $responseUrlsIdPage = $this->get('/urls/2');
        $responseUrlsIdPage->assertStatus(404);
    }

    public function testAddAndValidationUrl()
    {
        $responseHomePage = $this->get('/');
        $this->post('/add', ['url' => ['name' => 'test']]);

        $this->assertSame(0, DB::table('urls')->count());

        $this->post('/add', ['url' => ['name' => 'http://test.com']]);
        $this->post('/add', ['url' => ['name' => 'http://test.com']]);
        $this->post('/add', ['url' => ['name' => 'test']]);

        $this->assertSame(1, DB::table('urls')->count());
    }
}
