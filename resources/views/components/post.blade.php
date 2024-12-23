<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
    <div class="post-container">
        <img class="avatar-tiny" src="{{$post->user->avatar}}"/>
        <strong>{{$post->title}}</strong>
        <span class="text-muted small">
            @if (!isset($hideAuthor))
                by {{$post->user->username}}
            @endif
            on {{$post->created_at->format('j/n/Y')}} Updated {{$post->updated_at->diffForHumans() }}
        </span>
    </div>
</a>
