<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $action = $this->route()->getActionMethod();
        if ($action === 'store') {
            Log::info("User {$user->id} is attempting to create a new post");
            return true;
        }
        $postId = $this->route('post');
        Log::info('Checking permissions.........................................');
        $post = Post::find($postId)->first();
        $postUserId = $post->user_id;
        if (!$post) {
            Log::info('Post not found');
            return false;
        }

        $canUpdate = $this->user()->can('update', $post);
        $canDelete = $this->user()->can('delete', $post);
        Log::info("User ID: {$user->id}, Post User ID: {$post->user->id}");
        Log::info('User can update: ' . ($canUpdate ? 'true' : 'false') . ',Can delete: ' . ($canDelete ? 'true' : 'false'));
        Log::info("Checking permissions: User ID: {$user->id}, Post User ID: {$post->user_id}, Is Admin: " . ($user->is_admin ? 'true' : 'false') . ' Post ID:' . $post->id);
        return match ($action) {
            'update' => $canUpdate,
            'delete' => $canDelete,
            default => $canDelete || $canUpdate,
        };
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'body' => 'required'
        ];
    }
}
