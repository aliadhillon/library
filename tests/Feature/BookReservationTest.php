<?php

namespace Tests\Feature;

use App\Book;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        parent::setUp();
    }
    
    public function test_a_book_can_be_added_to_library()
    {
        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
        ];

        $response = $this->post('/books', $data);

        $response->assertOk();

        $this->assertCount(1, Book::all());
    }

    public function test_title_is_required_for_new_book()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Ali Dhillon'
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_author_is_required_for_new_book()
    {
        $response = $this->post('/books', [
            'title' => 'Test title',
            'author' => ''
        ]);

        $response->assertSessionHasErrors(['author']);
    }

    public function test_a_book_can_be_updated()
    {
        $book = factory(Book::class)->create();

        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
        ];

        $response = $this->patch("/books/{$book->id}", $data);

        $response->assertOk();

        $book->refresh();

        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['author'], $book->author);
    }

    public function test_a_book_cannot_be_updated_without_title()
    {
        $book = factory(Book::class)->create();

        $data = [
            'author' => $this->faker->name,
        ];

        $response = $this->patch("/books/{$book->id}", $data);
        
        $response->assertSessionHasErrors(['title' => __('validation.required', ['attribute' => 'title'])]);
    }

    public function test_a_book_cannot_be_updated_without_author()
    {
        $book = factory(Book::class)->create();

        $data = [
            'title' => $this->faker->words(3, true),
        ];

        $response = $this->patch("/books/{$book->id}", $data);

        $response->assertSessionHasErrors(['author' => __('validation.required', ['attribute' => 'author'])]);
    }
}
