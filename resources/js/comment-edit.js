document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-comment');
    const editCommentModal = document.getElementById('editCommentModal');
    const editCommentForm = document.getElementById('editCommentForm');
    const editCommentBody = document.getElementById('editCommentBody');

    // Assuming postId is passed from the Blade view
    const postId = document.getElementById('postId').value;

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            const commentBody = this.getAttribute('data-comment-body');

            editCommentBody.value = commentBody;
            editCommentForm.action = `/post/${postId}/comments/${commentId}`;

            $(editCommentModal).modal('show');
        });
    });
});
