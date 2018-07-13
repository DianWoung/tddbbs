@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card card-default">
                    <div class="card-header">forum Threads</div>

                    <div class="card-body">
                        @foreach($threads as $thread)
                            <article>
                                <a href="{{ $thread->path() }}">
                                    <h4>{{ $thread->title }}</h4>
                                </a>
                                <div class="body">{{ $thread->body }}</div>
                            </article>

                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection