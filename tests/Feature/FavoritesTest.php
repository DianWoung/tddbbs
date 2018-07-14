<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        try {
            $this->post('replies/' . $this->reply->id . '/favorites');
        }catch (\Exception $e){
            $this->fail('did not expect to insert the same record set twice');
        }
        $this->assertCount(1, $this->reply->favorites);
    }

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }
}
