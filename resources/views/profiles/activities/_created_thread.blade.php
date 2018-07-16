@component('profiles.activities.activity')
    @slot('header')
        {{ $profileUser->name }} 发表了
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent