<x-layout :doctitle="$doctitle">
    <div class="container py-md-5 container--narrow">
        <h2>

            <img class="avatar-small" src="{{$sharedData['avatar']}}"/> {{$sharedData['username']}}
            @if(auth()->user()->username == $sharedData['username'])
{{--                <a href="/profile/avatar/update" class="btn btn-secondary btn-sm">Manage Avatar</a>--}}
            @elseif($sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['username'])
                <button class="btn btn-secondary btn-sm" disabled>Followed <i class="fas fa-user-check"></i></button>
                <form class="ml-2 d-inline" action="/remove-follow/{{$sharedData['username']}}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm">Unfollow <i class="fas fa-user-times"></i></button>
                </form>
            @else
                <form class="ml-2 d-inline" action="/create-follow/{{$sharedData['username']}}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                </form>
            @endif
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{$sharedData['username']}}"
               class="profile-nav-link nav-item nav-link {{Request::segment(3) == "" ? "active" : ""}}">Posts: {{$sharedData['postCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/followers"
               class="profile-nav-link nav-item nav-link {{Request::segment(3) == "followers" ? "active" : ""}}">Followers: {{$sharedData['followerCount']}}</a>
            <a href="/profile/{{$sharedData['username']}}/following"
               class="profile-nav-link nav-item nav-link {{Request::segment(3) == "following" ? "active" : ""}}">Following: {{$sharedData['followingCount']}}</a>
            @if(auth()->user()->username == $sharedData['username'])
            <a href="/profile/{{$sharedData['username']}}/edit"
               class="profile-nav-link nav-item nav-link {{Request::segment(3) == "edit" ? "active" : ""}}">Edit Profile</a>
            @endif
        </div>

        <div class="profile-slot-container">
            {{$slot}}
        </div>
    </div>
</x-layout>

