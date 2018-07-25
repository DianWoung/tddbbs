@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10">
        <div class="card-header">
            <h1>
                {{ $profileUser->name }}
                <small>注册于{{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>
         @foreach($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>
                @foreach($activity as $record)
                    @if(view()->exists("profiles.activities._{$record->type}"))
                        @include("profiles.activities._{$record->type}", ['activity' => $record])
                    @endif
                 @endforeach
             @empty
                 <p>There is no activity for this user yet.</p>
         @endforeach

         @foreach($threads as $thread)
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> 发表于
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                        </span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                        <form action="{{ $thread->path() }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-link">Delete Thread</button>
                        </form>

                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection