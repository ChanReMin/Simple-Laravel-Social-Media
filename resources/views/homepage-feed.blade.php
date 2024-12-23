<x-layout>
    <div class="container py-md-5">

        @unless($posts->isEmpty())
            <h2 class="text-center mb-4">Latest post</h2>
            <div class="list-group">
                @foreach($posts as $post)
                    <x-post :post="$post"/>
                @endforeach
            </div>

            {{$posts->links() }}

        @else
            <div class="text-center">
                <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
                <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you
                    don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo;
                    feature in the top menu bar to find content written by people with similar interests and then
                    follow them.</p>
                <a href="/create-post" class="btn btn-primary">Create a post</a>
            </div>
        @endunless

    </div>
</x-layout>
