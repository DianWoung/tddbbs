@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('threads._topic')


            <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>
            <div class="col-md-4">
            <div class="card">
            <div class="card-body">
                <p>
                    <a href="#">{{ $thread->creator->name }}</a> 发布于 {{ $thread->created_at->diffForHumans() }},
                    当前共有 <span v-text="repliesCount"></span>个回复。
                </p>

                <p>
                    <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                    <button class="btn btn-default" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'">Lock</button>
                </p>
            </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection