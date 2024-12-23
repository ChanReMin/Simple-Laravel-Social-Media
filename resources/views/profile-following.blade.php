<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Following">
    <div class="list-group">
        @foreach($followings as $following)
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a href="/profile/{{$following->userBeingFollowed->username}}" class="text-decoration-none text-dark">
                    <img class="avatar-tiny" src="{{$following->userBeingFollowed->avatar}}"/>
                    {{$following->userBeingFollowed->username}}
                </a>
                <div class="btn-group">
                    @if($following->followed_user === auth()->user()->id)
                        <button style="display:none;"></button>
                    @elseif($following->following_state->isEmpty())
                        <form class="ml-2" action="/create-follow/{{$following->username}}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                        </form>
                    @endif
                    @foreach($following->following_state as $follow)
                        @if($follow->user_id === auth()->id())
                            <button class="btn btn-secondary btn-sm" disabled>Followed <i class="fas fa-user-check"></i></button>
                            <form class="ml-2" action="/remove-follow/{{$following->username}}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">Unfollow <i class="fas fa-user-times"></i></button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    {{$followings->links()}}
</x-profile>
