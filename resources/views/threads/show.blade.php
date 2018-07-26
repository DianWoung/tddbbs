@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                        <h5 class="flex">
                        <a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>发表了:
                        {{ $thread->title }}
                        @can('update',$thread)
                        </h5>
                            <div>
                            <form action="{{ $thread->path() }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-link">Delete Thread</button>
                            </form>
                            </div>
                        @endcan
                    </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>

            <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>
            {{--<div class="col-md-8 col-md-offset-2">--}}
                {{--@foreach ($replies as $reply)--}}
                    {{--@include('threads._reply')--}}
                {{--@endforeach--}}

                {{--{{ $replies->links() }}--}}
            {{--</div>--}}

        @if(auth()->check())

                <div class="col-md-8 col-md-offset-2">
                    <form method="post" action="{{ $thread->path() . '/replies' }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="说点什么吧..." rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </div>

        @else
            <p class="text-center">请先<a href="{{ route('login') }}">登录</a>，然后再发表回复 </p>
         @endif


        <div class="col-md-4">
            <div class="card">
            <div class="card-body">
                <p>
                    <a href="#">{{ $thread->creator->name }}</a> 发布于 {{ $thread->created_at->diffForHumans() }},
                    当前共有 <span v-text="repliesCount"></span>个回复。
                </p>
            </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection