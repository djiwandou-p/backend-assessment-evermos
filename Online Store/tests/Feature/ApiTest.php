<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Container;
use Tests\Feature\PlayerTest;

class ApiTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testFallback()
    {
    	$response = $this->json('GET', '/api/');
        $response->assertStatus(200)->assertSeeText('Hai');

    	$response = $this->json('GET', '/api/v1');
    	$expect = [
	      	"header" => [
	          	"code" => 404,
	          	"message" => "Page Not Found. If error persists, contact ranakrisna17031995@gmail.com",
	          	"status" => false
	      	]
	  	];
        $response->assertStatus(404)->assertJson($expect);
    }
}
