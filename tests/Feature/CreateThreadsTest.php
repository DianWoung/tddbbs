<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    /** @test */
   public function an_authenticated_user_can_create_new_forum_threads()
   {
       $this->signIn();

       $response = $this->post('/threads', $this->thread->toArray());

       $this->get($response->headers->get('Location'))
           ->assertSee($this->thread->title)
           ->assertSee($this->thread->body);
   }

   /** @test */
   public function guests_may_not_create_threads()
   {
       $this->withExceptionHandling();

       $this->get('/threads/create')
           ->assertRedirect('/login');

       $this->post('/threads')
           ->assertRedirect('/login');
   }

   /** @test */
    public function a_thread_requires_a_title()
    {


        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel',2)->create(); // 新建两个 Channel，id 分别为 1 跟 2

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])  // channle_id 为 999，是一个不存在的 Channel
        ->assertSessionHasErrors('channel_id');
    }

    public function a_thread_can_be_deleted()
    {
        $this->signIn();

        $this->json('DELETE', $this->thread->path());

        $this->assertDatabaseMissing('threads', $this->thread->toArray());
    }


    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $this->delete($this->thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($this->thread->path())->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread',['user_id' => auth()->id()]);
        $reply = create('App\Reply',['thread_id' => $thread->id]);

        $response =  $this->json('DELETE',$thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads',['id' => $thread->id]);
        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
    }
}
