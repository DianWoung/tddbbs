<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{

    /** @test */
   public function a_user_can_view_all_threads()
   {

        //测试查看全部
     $this->get('/threads')
         ->assertSee($this->thread->title);

   }
   /** @test */
   public function a_user_can_read_a_single_thread()
   {
       //测试单个
       $this->get($this->thread->path())
           ->assertSee($this->thread->title);
   }
    /** @test */
   public function a_user_can_read_replies_that_are_associated_with_a_thread()
   {
        //测试回复
       $reply = factory('App\Reply')
           ->create(['thread_id' => $this->thread->id]);

       $this->get($this->thread->path())
           ->assertSee($reply->body);
   }

    /** @test */
   public function a_user_can_filter_threads_according_to_a_channel()
   {
       $channel = create('App\Channel');
       $threadInChannel = create('App\Thread', ['channel_id' => $channel]);

       $this->get('/threads/'. $channel->slug)
           ->assertSee($threadInChannel->title)
           ->assertDontSee($this->thread->title);
   }
    /** @test */
   public function a_user_can_filter_threads_by_any_username()
   {
       $this->signIn(create('App\User', ['name' => 'NoNo1']));

       $threadByNoNo1 = create('App\Thread', ['user_id' => auth()->id()]);

       $this->get('threads?by=NoNo1')
           ->assertSee($threadByNoNo1->title)
           ->assertDontSee($this->thread->title);
   }
}
