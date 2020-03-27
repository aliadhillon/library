<?php

namespace Tests\Feature;

use App\Book;
use App\Exceptions\ReservationNotFoundException;
use App\User;
use Tests\TestCase;
use App\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_checked_out_by_signed_in_user()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkout/' . $book->id);

        $this->assertCount(1, $book->reservations);

        $reservation = $book->reservations->first();

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_out_at);
    }

    public function test_only_signed_in_users_can_checkout_a_book()
    {
        $book = factory(Book::class)->create();

        $this->post('/checkout/' . $book->id)
                ->assertRedirect(route('login'));

        $this->assertCount(0, $book->reservations);
    }

    public function test_only_books_available_can_be_checked_out()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkout/' . 100)
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_a_book_can_be_checked_in_by_signed_in_user()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        
        $book->checkout($user);

        $this->actingAs($user)
            ->post('/checkin/' . $book->id);

        $this->assertCount(1, $book->reservations);

        $reservation = $book->reservations->first();

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_in_at);
    }

    public function test_only_signed_in_users_can_checkin_a_book()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $this->post('/checkin/' . $book->id)
            ->assertRedirect(route('login'));

        $this->assertCount(1, $book->reservations);
        
        $reservation = $book->reservations->first();

        $this->assertNotNull($reservation);
        
        $this->assertNotNull($reservation->checked_out_at);
        $this->assertNull($reservation->checked_in_at);
    }

    public function test_only_books_available_can_be_checked_in()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkin/' . 100)
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_can_only_check_in_already_checked_out_books()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkin/' . $book->id)
            ->assertStatus(404);

        $this->assertCount(0, $book->reservations);
    }
}
