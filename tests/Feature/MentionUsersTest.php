<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'John']);
        $this->signIn($john);

        $jane = create('App\User', ['name' => 'Jane']);

        $reply = make('App\Reply',[
            'body' => '@Jane look at this.And alse @Luke'
        ]);

        $this->json('post', $this->thread->path() . '/replies', $reply->toArray());
        $this->assertCount(1, $jane->notifications);
    }

}
