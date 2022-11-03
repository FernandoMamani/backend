<?php

namespace Tests\Feature;

 use App\Models\Book;
 use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
   use RefreshDatabase;
   /** @test */
    function  can_get_all_books(){
        $books = Book::factory(4)->create();
       // $this->get('/api/books')->dump();
        $response = $this->get(route('books.index'))->dump();
        $response->assertJsonFragment([
            'title' => $books['0']->title
        ])->assertJsonFragment([
            'title' => $books['1']->title
        ]);
    }
    /** @test */
    function  can_get_one_book(){
        $book = Book::factory()->create();
        // $this->get('/api/books')->dump();
        $response = $this->get(route('books.show',$book))->dump();
        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }
    /** @test */
    function  can_create_books(){
        $this->postJson(route('books.store'),[])
            ->assertJsonValidationErrorFor('title');
        $response = $this->postJson(route('books.store'),[
            'title' =>'My new book'
        ])->assertJsonFragment([
            'title' => 'My new book'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'My new book'
        ]);
    }
    /** @test */
    function  can_update_books(){
        $book = Book::factory()->create();
        $this->patchJson(route('books.update',$book),[])
            ->assertJsonValidationErrorFor('title');

        $response = $this->patchJson(route('books.update',$book),[
            'title' =>'Edited book'
        ])->assertJsonFragment([
            'title' => 'Edited book'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'Edited book'
        ]);
    }
    /** @test */
    function  can_delete_books(){
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy',$book))
            ->assertNoContent();
        $this->assertDatabaseCount('books',0);
    }
}
