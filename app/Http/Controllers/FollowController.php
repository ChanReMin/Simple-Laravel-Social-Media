<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followed_user = $user->id;
        $newFollow->save();
        return back()->with('success','You have followed '.$user->username);
    }
    public function removeFollow(User $user)
    {
        Follow::where([['user_id','=',auth()->user()->id],['followed_user','=',$user->id]])->delete();
        return back()->with('success','You have unfollowed '.$user->username);
    }
}
