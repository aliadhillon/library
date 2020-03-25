<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        parent::setUp();
    }

    public function test_an_author_can_be_created()
    {
        $data = [
            'name' => $this->faker->name,
            'dob' => $this->faker->date(),
        ];    

        $this->post(route('authors.store'), $data);

        $author = Author::find(1);

        $this->assertNotNull($author);

        $this->assertInstanceOf(Carbon::class, $author->dob);
    }
}
