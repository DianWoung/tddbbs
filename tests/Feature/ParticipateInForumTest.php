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

        $this->assertDatabaseHas('replies', ['body' => $this->reply->body]);

        $this->assertEquals(1,$this->thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $reply = make('App\Reply', ['body' => null]);

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
        $this->assertEquals(0,$reply->thread->fresh()->replies_count);
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

    /** @test */
    public function replies_contain_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply',[
            'body' => 'something forbidden'
        ]);

        $this->post($thread->path() . '/replies',$reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply',[
            'body' => 'My simple reply.'
        ]);

        $this->post($thread->path() . '/replies',$reply->toArray())
            ->assertStatus(201);

        $this->post($thread->path() . '/replies',$reply->toArray())
            ->assertStatus(422);
    }

}
