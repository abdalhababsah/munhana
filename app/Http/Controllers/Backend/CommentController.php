<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'comment' => 'required|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();

        $comment = Comment::create($validated);
        $comment->load('user');

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.comment_added_successfully'),
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'user_initials' => strtoupper(substr($comment->user->name, 0, 2)),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_edit' => auth()->id() === $comment->user_id || auth()->user()->role === 'admin',
                ],
            ]);
        }

        // Return redirect for regular form submissions
        return redirect()->back()->with('success', __('messages.comment_added_successfully'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        // Check if user is comment owner or admin
        if (auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.unauthorized'),
                ], 403);
            }
            return redirect()->back()->with('error', __('messages.unauthorized'));
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.comment_updated_successfully'),
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'updated_at' => $comment->updated_at->diffForHumans(),
                ],
            ]);
        }

        return redirect()->back()->with('success', __('messages.comment_updated_successfully'));
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        // Check if user is comment owner or admin
        if (auth()->id() !== $comment->user_id && auth()->user()->role !== 'admin') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.unauthorized'),
                ], 403);
            }
            return redirect()->back()->with('error', __('messages.unauthorized'));
        }

        $comment->delete();

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.comment_deleted_successfully'),
            ]);
        }

        return redirect()->back()->with('success', __('messages.comment_deleted_successfully'));
    }
}
