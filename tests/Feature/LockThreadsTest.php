<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    /** @test */
    public function once_locked_thread_may_not_receive_new_replies()
    {

        $this->signIn($this->user);
        $this->thread->lock();
        $this->post($this->thread->path() . '/replies', [
            'body' => 'foobar',
            'user_id' => auth()->id()
        ])->assertStatus(201);
    }

    /** @test */
    public function non_administrator_may_not_lock_threads()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread',[
            'user_id' => auth()->id()
        ]);
        $this->post(route('locked-threads.store',$thread))->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread',['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store',$thread));

        $this->assertTrue(!! $thread->fresh()->locked);
    }

}
