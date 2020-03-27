<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_an_author_can_be_created()
    {
        $data = [
            'name' => $this->faker->name,
            'dob' => $this->faker->date(),
        ];

        $this->post(route('authors.store'), $data);

        $author = Author::first();

        $this->assertNotNull($author);

        $this->assertInstanceOf(Carbon::class, $author->dob);
    }

    public function test_a_name_is_required()
    {
        $data = [
            'dob' => $this->faker->date(),
        ];

        $this->post(route('authors.store'), $data)
                ->assertSessionHasErrors('name');
    }

    public function test_a_dob_is_required()
    {
        $data = [
            'name' => $this->faker->name,
        ];

        $this->post(route('authors.store'), $data)
                ->assertSessionHasErrors('dob');
    }
}
