<?php

namespace App\Http\Controllers;

use App\Events\ExampleEvent;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $validateData['password'] = bcrypt($validateData['password']);
        $user = User::create($validateData);
        return redirect('/')->with('success', 'Registration Successful');
    }

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'login_username' => ['required'],
            'login_password' => ['required']
        ]);

        if (auth()->attempt(['username' => $validateData['login_username'], 'password' => $validateData['login_password']])) {
            $request->session()->regenerate();
            event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
            return redirect('/')->with('success', 'Login Successful');
        }

        return redirect('/')->with('error', 'Invalid Credentials');
    }
    public function loginAPI(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);
        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first();
            $token = $user->createToken('authToken')->plainTextToken;
            return $token;
        }
        return response()->json(['status' => 'error', 'message' => 'Authentication failed'], 401);
    }

    public function logout()
    {
        event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'Logout Successful');
    }
    public function sharedData($user)
    {
        $postCount = $user->posts()->count();
        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followed_user', '=', $user->id]])->count();

            View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing,
                'avatar' => $user->avatar,
                'user' => $user,
                'username' => $user->username,
                'postCount' => $postCount,
                'followerCount' => $user->followers()->count(),
                'followingCount' => $user->followings()->count()]);
        } else {
            View::share('sharedData', [
                'avatar' => $user->avatar,
                'user' => $user,
                'username' => $user->username,
                'postCount' => $postCount,
                'followerCount' => $user->followers()->count(),
                'followingCount' => $user->followings()->count()]);

        }
    }
    public function viewProfile(User $user)
    {
        $posts = $user->posts()->latest()->paginate(5);
        $this->sharedData($user);
        return view('profile-posts', ['posts' => $posts]);
    }
    public function profileFollowers(User $user)
    {
        $login_user = auth()->user();
        $posts = $user->posts()->latest()->get();
        $followers = $user->followers()->latest()->paginate(5);
        foreach ($followers as $follower) {
//            $follower['following'] = $login_user->followings()->where('followed_user', $follower->user_id)->get();
            $follower['following_state'] = Follow::where([['user_id', '=', $login_user->id], ['followed_user', '=', $follower->user_id]])->get();
            $follower['username'] = User::where('id', $follower->user_id)->select('username')->first()->username;
        }
        $this->sharedData($user);
//        return $followers;
        return view('profile-followers', ['followers' => $followers]);
    }
    public function profileFollowing(User $user)
    {
        $login_user = auth()->user();
        $posts = $user->posts()->latest()->get();
        $this->sharedData($user);
        $followings = $user->followings()->latest()->paginate(5);
        foreach ($followings as $following) {
            $following['following_state'] = Follow::where([['user_id', '=', $login_user->id], ['followed_user', '=', $following->followed_user]])->get();
            $following['username'] = User::where('id', $following->followed_user)->select('username')->first()->username;
        }
//        return $followings;
        return view('profile-following', ['followings' => $followings]);
    }
    public function showEditProfileForm(User $user)
    {
        if(auth()->user()->id!= $user->id) {
            return redirect()->route('user.profile', $user)->with('error', 'You are not authorized to edit this profile.');
        }
//        if (!auth()->user()->can('update', $user)) {
//            return redirect()->route('user.profile', $user)->with('error', 'You are not authorized to edit this profile.');
//        }
        $this->sharedData($user);
        return view('profile-editProfile', ['user' => $user]);
    }
    public function update(Request $request, User $user)
    {

        $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        $this->sharedData($user);
        return redirect('/profile/' . $user->username)->with('success', 'Profile updated successfully');
    }
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:3000']
        ]);
        $user = auth()->user();

        $filename = 'user-' . $user->id . '-' . 'avatar' . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(300, 300)->encode('jpg', 80);
        Storage::put('public/avatars/' . $filename, (string)$imgData);

        $oldAvatar = $user->avatar;
        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != 'https://via.placeholder.com/150') {
            Storage::delete(str_replace("storage/", "public", $oldAvatar));
        }
        return redirect('/profile/' . $user->username)->with('success', 'Avatar updated successfully');

    }
    public function showAvatarForm()
    {
        return view('avatar-form');
    }
    public function getAllUsers()
    {
        $login_user = auth()->user();

        $users = User::where('id', '!=', auth()->id())->paginate(10);

        if (auth()->check()) {
            foreach ($users as $user) {
                $user->following = $user->followers()->where('user_id', $login_user->id)->get();
            }
        }
        return view('all-users', ['users' => $users]);
//        return $users;
    }
    public function getAllUsersAPI()
    {
        $users = User::where('id', '!=', auth()->id())->paginate(10);
        return $users;
    }
    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->paginate(10)]);
        } else {
            return view('homepage');
        }
    }
}



