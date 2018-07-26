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


            <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>
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