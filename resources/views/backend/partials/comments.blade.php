{{-- Comments Component --}}
{{-- Usage: @include('backend.partials.comments', ['commentable' => $model, 'comments' => $model->comments]) --}}

<div class="card" id="comments-section">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('messages.comments') }}
            <span class="text-sm font-normal text-gray-500">({{ $comments->count() }})</span>
        </h4>
    </div>
    <div class="p-6">
        {{-- Comments List --}}
        <div id="comments-list" class="space-y-4 mb-6">
            @forelse($comments as $comment)
            <div class="comment-item border-b pb-4 last:border-b-0" data-comment-id="{{ $comment->id }}">
                <div class="flex items-start gap-3">
                    {{-- User Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-primary">
                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                        </span>
                    </div>

                    {{-- Comment Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-500 ms-2">
                                    {{ $comment->created_at->diffForHumans() }}
                                    @if($comment->created_at != $comment->updated_at)
                                        <span class="italic">({{ __('messages.edited') }})</span>
                                    @endif
                                </span>
                            </div>

                            {{-- Edit/Delete Actions --}}
                            @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="text-warning hover:text-warning/80 edit-comment-btn"
                                        data-comment-id="{{ $comment->id }}"
                                        title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </button>
                                <button type="button"
                                        class="text-danger hover:text-danger/80 delete-comment-btn"
                                        data-comment-id="{{ $comment->id }}"
                                        title="{{ __('messages.delete') }}">
                                    <i class="uil uil-trash text-lg"></i>
                                </button>
                            </div>
                            @endif
                        </div>

                        {{-- Comment Text --}}
                        <div class="comment-text">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                        </div>

                        {{-- Edit Form (Hidden by default) --}}
                        <div class="edit-form hidden mt-2">
                            <form class="update-comment-form">
                                @csrf
                                @method('PUT')
                                <textarea name="comment"
                                          rows="3"
                                          class="form-input w-full mb-2"
                                          required>{{ $comment->comment }}</textarea>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="uil uil-check me-1"></i>
                                        {{ __('messages.update') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light cancel-edit-btn">
                                        {{ __('messages.cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="uil uil-comments-alt text-6xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">{{ __('messages.no_comments_yet') }}</p>
            </div>
            @endforelse
        </div>

        {{-- Add Comment Form --}}
        <div class="border-t pt-6">
            <h5 class="font-semibold text-gray-900 mb-3">{{ __('messages.add_comment') }}</h5>
            <form id="add-comment-form" method="POST" action="{{ route('backend.comments.store') }}">
                @csrf
                <input type="hidden" name="commentable_type" value="{{ get_class($commentable) }}">
                <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">

                <div class="mb-3">
                    <textarea name="comment"
                              id="comment-input"
                              rows="4"
                              class="form-input w-full @error('comment') border-danger @enderror"
                              placeholder="{{ __('messages.write_your_comment') }}"
                              required></textarea>
                    @error('comment')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-message me-2"></i>
                        {{ __('messages.post_comment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for AJAX Comment Functionality --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentsSection = document.getElementById('comments-section');
    if (!commentsSection) return;

    // AJAX Comment Submission
    const addCommentForm = document.getElementById('add-comment-form');
    if (addCommentForm) {
        addCommentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="uil uil-spinner-alt animate-spin me-2"></i>{{ __("messages.posting") }}';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Clear form
                    document.getElementById('comment-input').value = '';

                    // Add new comment to list
                    const commentsList = document.getElementById('comments-list');
                    const emptyState = commentsList.querySelector('.text-center.py-8');

                    if (emptyState) {
                        emptyState.remove();
                    }

                    const newComment = createCommentElement(data.comment);
                    commentsList.insertAdjacentHTML('beforeend', newComment);

                    // Update comment count
                    const countSpan = commentsSection.querySelector('.card-title span');
                    const currentCount = parseInt(countSpan.textContent.match(/\d+/)[0]);
                    countSpan.textContent = `(${currentCount + 1})`;

                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || '{{ __("messages.error_occurred") }}', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('{{ __("messages.error_occurred") }}', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }

    // Edit Comment
    commentsSection.addEventListener('click', function(e) {
        if (e.target.closest('.edit-comment-btn')) {
            const btn = e.target.closest('.edit-comment-btn');
            const commentItem = btn.closest('.comment-item');
            const commentText = commentItem.querySelector('.comment-text');
            const editForm = commentItem.querySelector('.edit-form');

            commentText.classList.add('hidden');
            editForm.classList.remove('hidden');
        }
    });

    // Cancel Edit
    commentsSection.addEventListener('click', function(e) {
        if (e.target.closest('.cancel-edit-btn')) {
            const btn = e.target.closest('.cancel-edit-btn');
            const commentItem = btn.closest('.comment-item');
            const commentText = commentItem.querySelector('.comment-text');
            const editForm = commentItem.querySelector('.edit-form');

            commentText.classList.remove('hidden');
            editForm.classList.add('hidden');
        }
    });

    // Update Comment
    commentsSection.addEventListener('submit', async function(e) {
        if (e.target.classList.contains('update-comment-form')) {
            e.preventDefault();

            const form = e.target;
            const commentItem = form.closest('.comment-item');
            const commentId = commentItem.dataset.commentId;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="uil uil-spinner-alt animate-spin me-1"></i>{{ __("messages.updating") }}';

            try {
                const response = await fetch(`{{ url('backend/comments') }}/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Update comment text
                    const commentText = commentItem.querySelector('.comment-text p');
                    commentText.textContent = data.comment.comment;

                    // Hide edit form, show comment text
                    const commentTextDiv = commentItem.querySelector('.comment-text');
                    const editForm = commentItem.querySelector('.edit-form');
                    commentTextDiv.classList.remove('hidden');
                    editForm.classList.add('hidden');

                    // Update timestamp with edited indicator
                    const timeSpan = commentItem.querySelector('.text-xs.text-gray-500');
                    if (!timeSpan.textContent.includes('{{ __("messages.edited") }}')) {
                        timeSpan.innerHTML += ' <span class="italic">({{ __("messages.edited") }})</span>';
                    }

                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || '{{ __("messages.error_occurred") }}', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('{{ __("messages.error_occurred") }}', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }
    });

    // Delete Comment
    commentsSection.addEventListener('click', async function(e) {
        if (e.target.closest('.delete-comment-btn')) {
            if (!confirm('{{ __("messages.confirm_delete_comment") }}')) {
                return;
            }

            const btn = e.target.closest('.delete-comment-btn');
            const commentItem = btn.closest('.comment-item');
            const commentId = commentItem.dataset.commentId;

            try {
                const response = await fetch(`{{ url('backend/comments') }}/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Remove comment from DOM
                    commentItem.remove();

                    // Update comment count
                    const countSpan = commentsSection.querySelector('.card-title span');
                    const currentCount = parseInt(countSpan.textContent.match(/\d+/)[0]);
                    countSpan.textContent = `(${currentCount - 1})`;

                    // Show empty state if no comments left
                    const commentsList = document.getElementById('comments-list');
                    if (commentsList.querySelectorAll('.comment-item').length === 0) {
                        commentsList.innerHTML = `
                            <div class="text-center py-8">
                                <i class="uil uil-comments-alt text-6xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">{{ __('messages.no_comments_yet') }}</p>
                            </div>
                        `;
                    }

                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || '{{ __("messages.error_occurred") }}', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('{{ __("messages.error_occurred") }}', 'error');
            }
        }
    });

    // Helper function to create comment HTML
    function createCommentElement(comment) {
        const canEdit = comment.can_edit;
        const editButtons = canEdit ? `
            <div class="flex items-center gap-2">
                <button type="button"
                        class="text-warning hover:text-warning/80 edit-comment-btn"
                        data-comment-id="${comment.id}"
                        title="{{ __('messages.edit') }}">
                    <i class="uil uil-edit text-lg"></i>
                </button>
                <button type="button"
                        class="text-danger hover:text-danger/80 delete-comment-btn"
                        data-comment-id="${comment.id}"
                        title="{{ __('messages.delete') }}">
                    <i class="uil uil-trash text-lg"></i>
                </button>
            </div>
        ` : '';

        return `
            <div class="comment-item border-b pb-4" data-comment-id="${comment.id}">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-primary">${comment.user_initials}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold text-gray-900">${comment.user_name}</span>
                                <span class="text-xs text-gray-500 ms-2">${comment.created_at}</span>
                            </div>
                            ${editButtons}
                        </div>
                        <div class="comment-text">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">${comment.comment}</p>
                        </div>
                        <div class="edit-form hidden mt-2">
                            <form class="update-comment-form">
                                @csrf
                                @method('PUT')
                                <textarea name="comment" rows="3" class="form-input w-full mb-2" required>${comment.comment}</textarea>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="uil uil-check me-1"></i>{{ __('messages.update') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light cancel-edit-btn">{{ __('messages.cancel') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        // You can implement your preferred notification system here
        // For now, using simple alert
        alert(message);
    }
});
</script>
@endpush
