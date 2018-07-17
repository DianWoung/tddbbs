@component('profiles.activities.activity')
    @slot('header')
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} 对回复进行了点赞
        </a>
    @endslot
    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent