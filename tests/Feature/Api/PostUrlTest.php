<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UrlHash;

class PostUrlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }
    
    /**
     * test that the URL creation endpoint works
     * 
     * @return void
     */
    public function testItCreatesOneUrl()
    {
        $response = $this->postJson('/api/url_hash', ['url' => 'https://www.google.com']);
        $response->assertStatus(200);
        $response->assertJson(['url' => 'https://www.google.com']);
        $this->assertTrue(true);
    }

    public function testItFetchesOneHundredEntities() {
        $response = $this->getJson('/api/url_hash/popular');
        $response->assertOk();
        $data = $response['data'];
        $this->assertEquals(sizeOf($data), 100);
    }
}
