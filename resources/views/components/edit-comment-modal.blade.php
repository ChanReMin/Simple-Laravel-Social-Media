<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCommentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editCommentBody">Comment</label>
                        <textarea class="form-control" id="editCommentBody" name="body" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-comment');
        const editCommentModal = document.getElementById('editCommentModal');
        const editCommentForm = document.getElementById('editCommentForm');
        const editCommentBody = document.getElementById('editCommentBody');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const commentId = this.getAttribute('data-comment-id');
                const commentBody = this.getAttribute('data-comment-body');

                editCommentBody.value = commentBody;
                editCommentForm.action = `/post/{{$post->id}}/comments/${commentId}`;

                $(editCommentModal).modal('show');
            });
        });
    });
</script>
