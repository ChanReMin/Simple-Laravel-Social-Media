<x-profile :sharedData="$sharedData" doctitle="Edit {{$sharedData['username']}}'s Profile">
    <div class="grid-container">
        <div class="cols">
        <h2 class="text-center mb-3">Update Your Profile</h2>
            <form action="/profile/{{auth()->user()->username}}/edit" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ auth()->user()->username }}" required>
                    @error('username')
                    <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                    @error('email')
                    <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    @error('current_password')
                    <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                    @error('new_password')
                    <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
        <div class="cols">
            <div class="mt-4">
                <h3 class="text-center mb-3">Manage Avatar</h3>
                <div class="text-center">
                    <img src="{{ auth()->user()->avatar }}" alt="Current Avatar" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
                </div>
                <form action="/profile/avatar/update/{{auth()->user()->username}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 center">
                        <input type="file" name="avatar" id="avatar" required>
                        @error('avatar')
                        <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-secondary">Update Avatar</button>
                </form>
            </div>
        </div>
    </div>

</x-profile>
