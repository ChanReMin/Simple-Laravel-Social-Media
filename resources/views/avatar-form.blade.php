<x-layout doctitle="Manage your avatar">
    <div class="container container--narrow py-md=5">
        <h2 class="text-center mb-3">Upload a New Avatar</h2>
        <form action="/profile/avatar/update/{{auth()->user()->username}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar" id="avatar" required>
                @error('avatar')
                <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>