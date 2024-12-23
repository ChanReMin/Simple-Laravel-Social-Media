<x-layout doctitle="All users" :users="$users">
{{--    <div class="container py-md-6">--}}
        <div class="grid-container mx-auto">
            @foreach($users as $user)
                <div class="col">
                    @if($loop->iteration <= $user->count())
                        <div class="card">
                            <a href="/profile/{{$user->username}}" class="profileImage">
                                <img class="img" src="{{$user->avatar}}" alt="{{$user->username}}" width="150"
                                     height="150">
                            </a>
                            <div class="textContainer">
                                <p class="name">
                                    {{$user->username}}</p>
                            </div>
                            <div class="followButton-container">
                                @if($user->following->isEmpty())
                                    <form class="ml-2 d-inline " action="/create-follow/{{$user['username']}}"
                                          method="POST">
                                        @csrf
                                        <button class="btn btn-primary btn-sm mx-auto">Follow <i
                                                class="fas fa-user-plus"></i>
                                        </button>
                                    </form>
                                @endif
                                @foreach($user->following as $follow)
                                    @if($follow->user_id == auth()->id())
                                        <button class="btn btn-secondary btn-sm" disabled>Followed <i
                                                class="fas fa-user-check"></i></button>
                                        <form class="ml-2 d-inline" action="/remove-follow/{{$user['username']}}"
                                              method="POST">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Unfollow <i
                                                    class="fas fa-user-times"></i></button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                </div>
                @endif
            @endforeach
        </div>
        {{ $users->links() }}
{{--    </div>--}}
</x-layout>
