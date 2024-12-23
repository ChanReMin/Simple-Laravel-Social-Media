<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
        <div class="post-content border p-4 mb-4 rounded">
            <div class="d-flex justify-content-between">
                <h2 class="mb-4">{{$post->title}}</h2>
                @can('update', $post)
                    <span class="pt-2">
                        <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip"
                           data-placement="top"
                           title="Edit"><i
                                class="fas fa-edit"></i></a>
                        <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-post-button text-danger" data-toggle="tooltip"
                                    data-placement="top"
                                    title="Delete">
                            <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </span>
                @endcan
            </div>
            <p class="text-muted small mb-4">
                <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny"
                                                                  src="{{$post->user->avatar}}"/></a>
                Posted by <a
                    href="/profile/{{$post->user->username}}">{{$post->user->username}}</a> {{$post->created_at->format('j/n/Y H:i:s')}}
                @if($post->updatedBy !== NULL)
                    Updated by <a
                        href="/profile/{{$post->updatedBy->username}}">{{$post->updatedBy->username}}</a> {{$post->updated_at->format('j/n/Y H:i:s')}}
                @endif
            </p>

            <div class="body-content mb-5">
                {!!  $post->body !!}
            </div>
        </div>
        <div class="comments-section" style="font-size: 0.9rem;">
            @foreach($user_comments as $user_comment)
                <div class="media mb-3 pb-3 border-bottom">
                    <img class="d-flex mr-3 rounded-circle" src="{{$user_comment->user->avatar}}"
                         alt="{{$user_comment->user->username}}"
                         width="50">
                    <div class="media-body">
                        <h6 class="mt-0 mb-1">
                            <a href="/profile/{{$user_comment->user->username}}">{{$user_comment->user->username}}</a>
                            <small class="text-muted">{{$user_comment->created_at->diffForHumans()}}</small>
                        </h6>
                        <p class="mb-1">{{$user_comment->body}}</p>
                    </div>

                    @if(auth()->user()->id === $user_comment->user_id)
                        <a href="javascript:void(0);" class="text-primary mr-2 edit-comment" data-toggle="tooltip"
                           data-placement="top" title="Edit" data-comment-id="{{$user_comment->id}}"
                           data-comment-body="{{$user_comment->body}}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form class="delete-comment-form d-inline"
                              action="/post/{{$post->id}}/comments/{{$user_comment->id}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="delete-comment-button text-danger" data-toggle="tooltip" data-placement="top"
                                    title="Delete"><i
                                    class="fas fa-trash"></i></button>
                        </form>
                    @elseif(auth()->user()->is_admin)
                        <form class="delete-comment-form d-inline"
                              action="/post/{{$post->id}}/comments/{{$user_comment->id}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="delete-comment-button text-danger" data-toggle="tooltip" data-placement="top"
                                    title="Delete"><i
                                    class="fas fa-trash"></i></button>
                        </form>
                    @endif
                    <div id="editForm" style="display: none;">
                        <form id="commentEditForm" action="/post/{{$post->id}}/comments/{{$user_comment->id}}/update"
                              method="POST">
                            @csrf
                            <textarea name="comment" id="commentText" rows="4">{{ $user_comment->text }}</textarea>
                            <button type="submit">Submit</button>
                            <button type="button" id="cancelEdit">Cancel</button>
                        </form>
                    </div>
                </div>
            @endforeach
            {{$user_comments->links() }}
        </div>
        @auth
            <form class="mt-4" action="/post/{{$post->id}}/comments" method="POST">
                @csrf
                <div class="form-group">
                    <label for="commentBody">Add a comment</label>
                    <textarea class="form-control" id="commentBody" name="body" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </form>
        @endauth
    </div>
    @include('components.edit-comment-modal')

</x-layout>


