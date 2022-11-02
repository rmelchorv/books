<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function can_get_all_books()
    {
      //$book = Book::factory()->create();
        $books = Book::factory(4)->create();

      //dd($book);
      //dd($books->count());

      //$this->get('api/books')->dump();

      //dd(route('books.index'));
      //$this->get(route('books.index'))->dump();

        $this->getJson(route('books.index'))
             ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ])->assertJsonFragment([
                'title' => $books[2]->title
            ])->assertJsonFragment([
                'title' => $books[3]->title
            ]);
    }

    public function test_can_get_one_book()
    {
        $book = Book::factory()->create();

      //dd(route('books.show', $book));
        $this->getJson(route('books.show', $book))
             ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /** @test */
    public function can_create_book()
    {
        //Verificar que el libro tenga los campos requeridos
      //$this->postJson(route('books.store', []))
      //       ->assertJsonValidationErrorFor('title');
        //Verificar que el libro se insertó correctamente
        $this->postJson(route('books.store', [
            'title' => 'A new book'
        ]))->assertJsonFragment([
            'title' => 'A new book'
        ]);
        // Verificar que el libro se encuentra en la BD
        $this->assertDatabaseHas('books', [
            'title' => 'A new book'
        ]);
    }

    /** @test */
    public function can_upate_book()
    {
        $book = Book::factory()->create();

        //Verificar que el libro tenga los campos requeridos
      //$this->patchJson(route('books.update', $book), [])
      //       ->assertJsonValidationErrorFor('title');
        //Verificar que el libro se actualizó correctamente
        $this->patchJson(route('books.update', $book), [
            'title' => 'Updated book'
        ])->assertJsonFragment([
            'title' => 'Updated book'
        ]);
        // Verificar que el libro se encuentra en la BD
        $this->assertDatabaseHas('books', [
            'title' => 'Updated book'
        ]);
    }

    /** @test */
    public function can_delete_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
             ->assertNoContent();
        // Verificar que el libro se encuentra en la BD
        $this->assertDatabaseCount('books', 0);
    }
}
