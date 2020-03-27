<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function test_a_book_can_be_added_to_library()
    {
        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
        ];

        $response = $this->post(route('books.store'), $data);

        $book = Book::first();
        
        $this->assertNotNull($book);
        $this->assertDatabaseHas('books', [
            'id' => 1,
            'title' => $data['title'],
        ]);

        $response->assertRedirect(route('books.show', ['book' => $book]));
    }

    public function test_title_is_required_for_new_book()
    {
        $data = [
            'author' => $this->faker->name,
        ];

        $response = $this->post(route('books.store'), $data);

        $response->assertSessionHasErrors(['title' => __('validation.required', ['attribute' => 'title'])]);
    }

    public function test_author_is_required_for_new_book()
    {
        $data = [
            'title' => $this->faker->words(3, true),
        ];

        $response = $this->post(route('books.store'), $data);

        $response->assertSessionHasErrors(['author' => __('validation.required', ['attribute' => 'author'])]);
    }

    public function test_a_book_can_be_updated()
    {
        $book = factory(Book::class)->create();

        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
        ];

        $response = $this->patch(route('books.update', ['book' => $book]), $data);

        $book->refresh();

        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['author'], $book->author->name);

        $response->assertRedirect(route('books.show', ['book' => $book]));
    }

    public function test_a_book_cannot_be_updated_without_title()
    {
        $book = factory(Book::class)->create();

        $data = [
            'author' => $this->faker->name,
        ];

        $response = $this->patch(route('books.update', ['book' => $book]), $data);
        
        $response->assertSessionHasErrors(['title' => __('validation.required', ['attribute' => 'title'])]);
    }

    public function test_a_book_cannot_be_updated_without_author()
    {
        $book = factory(Book::class)->create();

        $data = [
            'title' => $this->faker->words(3, true),
        ];

        $response = $this->patch(route('books.update', ['book' => $book]), $data);

        $response->assertSessionHasErrors(['author' => __('validation.required', ['attribute' => 'author'])]);
    }

    public function test_a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();

        $response = $this->delete(route('books.destroy', ['book' => $book]));

        $this->assertDeleted($book);
        // $this->assertNull($book->fresh());

        $response->assertRedirect(route('books.index'));
    }

    public function test_a_new_author_is_automatically_added()
    {
        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
        ];

        $response = $this->post(route('books.store'), $data);

        $book = Book::first();
        $author = Author::first();

        $this->assertNotNull($book);
        $this->assertNotNull($author);
        $this->assertEquals($author->id, $book->author_id);
    }

    public function test_an_already_existed_author_can_be_added()
    {
        $author = factory(Author::class)->create();

        $data = [
            'title' => $this->faker->words(3, true),
            'author' => $author->name,
        ];

        $response = $this->post(route('books.store'), $data);

        $book = Book::first();

        $this->assertNotNull($book);
        $this->assertEquals($book->author_id, $author->id);
    }
}
