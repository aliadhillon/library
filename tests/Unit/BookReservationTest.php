<?php

namespace Tests\Unit;

use App\Book;
use App\User;
use Tests\TestCase;
use App\Reservation;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_book_can_be_checked_out()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $this->assertCount(1, $book->reservations);

        $reservation = Reservation::first();

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_out_at);
        $this->assertNull($reservation->checked_in_at);
    }

    public function test_a_book_can_be_returned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, $book->reservations);

        $reservation = Reservation::first();

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_in_at);
        $this->assertNotNull($reservation->checked_in_at);
    }

    public function test_a_user_can_checkout_a_book_twise()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(2, $book->reservations);

        $reservation = $book->reservations->get(1);

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_out_at);
        $this->assertNull($reservation->checked_in_at);

        $book->checkin($user);

        $book->refresh();

        $this->assertCount(2, $book->reservations);

        $reservation = $book->reservations->get(1);

        $this->assertNotNull($reservation);

        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);

        $this->assertNotNull($reservation->checked_out_at);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertNotNull($reservation->checked_in_at);      
    }

    public function test_check_in_without_check_out_should_throw_exception()
    {
        $this->expectException(Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);
    }
}
