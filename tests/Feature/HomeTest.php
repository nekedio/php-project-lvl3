<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHome(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }
}