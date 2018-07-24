<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    /** @test */
    public function unauthenticated_user_may_no_add_replies()
    {
        $this->withExceptionHandling()
            ->post($this->thread->path(). '/replies', $this->reply->toArray())
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn($this->user);

        $this->post($this->thread->path().'/replies', $this->reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->reply->body);
    }

    /** @test */
    public function a_reply_reqiures_a_body()
    {
        $this->withExceptionHandling()->signIn();


        $reply = make('App\Reply',['body' => null]);

        $this->post($this->thread->path() . '/replies',$reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $this->delete("/replies/{$this->reply->id}")
            ->assertRedirect('/login');
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply',['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $this->patch("/replies/{$this->reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$this->reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply =  create('App\Reply',['user_id' => auth()->id()]);

        $updateReply = "You have been changed,foo";

        $this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
    }
}
