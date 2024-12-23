<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Followers">
    <div class="list-group">
        @foreach($followers as $follower)
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <a href="/profile/{{$follower->userDoingTheFollowing->username}}" class="text-decoration-none text-dark">
                    <img class="avatar-tiny" src="{{$follower->userDoingTheFollowing->avatar}}"/>
                    {{$follower->userDoingTheFollowing->username}}
                </a>
                <div class="btn-group">
                    @if($follower->user_id === auth()->user()->id)
                        <button style="display:none;"></button>
                    @elseif($follower->following_state->isEmpty())
                        <form class="ml-2" action="/create-follow/{{$follower['username']}}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                        </form>
                    @endif
                    @foreach($follower->following_state as $follow)
                        @if($follow->user_id == auth()->id())
                            <button class="btn btn-secondary btn-sm" disabled>Followed <i class="fas fa-user-check"></i></button>
                            <form class="ml-2" action="/remove-follow/{{$follower['username']}}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">Unfollow <i class="fas fa-user-times"></i></button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    {{$followers->links()}}
</x-profile>
