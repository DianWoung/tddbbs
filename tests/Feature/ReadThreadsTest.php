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

   /** @test */
   public function a_user_can_filter_threads_by_popularity()
   {
       $threadWithTwoReplies = create('App\Thread');
       create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

       $threadWithThreeReplies = create('App\Thread');
       create('App\Reply',['thread_id'=>$threadWithThreeReplies->id],3);

       $threadWithNoReplies = $this->thread;

       // When I filter all threads by popularity
       $response = $this->getJson('threads?popularity=1')->json();

       // Then they should be returned from most replies to least.
       $this->assertEquals([3,2,1,0],array_column($response['data'],'replies_count'));
   }
    /** @test */
   public function a_user_can_request_all_replies_for_a_given_thread()
   {
       $thread = create('App\Thread');
       create('App\Reply',['thread_id' => $thread->id],40);

       $response = $this->getJson($thread->path() . '/replies')->json();

       $this->assertCount(20,$response['data']);
       $this->assertEquals(40,$response['total']);
   }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('App\Thread');
        create('App\Reply',['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1,$response['data']);
    }

    /** @test */
    public function we_record_a_new_visit_each_time_the_thread_is_read()
    {

        $this->assertSame(0,$this->thread->visits);

        $this->call('GET',$this->thread->path());

        $this->assertEquals(1,$this->thread->fresh()->visits);
    }


}
