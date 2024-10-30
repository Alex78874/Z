document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.parent-reply-form').forEach(form => {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const data = {
        post_id: this.querySelector('input[name="post_id"]').value,
        reply_content: this.querySelector('textarea[name="reply_content"]').value,
        parent_id: this.querySelector('input[name="parent_id"]').value
      };

      fetch(this.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(response => {
        if (response.success) {
          addCommentToUI(response.comment);
        } else {
          alert('Error posting reply: ' + response.message);
        }
      })
      .catch(error => {
        alert('Network error: ' + error.message);
      });
    });
  });
});

function addCommentToUI(comment) {
  const commentHtml = `
    <div class="comment" data-comment-id="${comment.id}">
      <div class="comment-user">
        <strong>${comment.username}</strong>
        <span class="comment-date">${comment.publication_date}</span>
      </div>
      <div class="comment-content">
        <p>${comment.content}</p>
      </div>
      <div class="comment-footer">
        <span class="like-count">${comment.like_count} Like(s)</span>
        <span class="reply-count">${comment.comment_count} Réponse(s)</span>
        <form method="post" action="/post/like" class="like-form">
          <input type="hidden" name="post_id" value="${comment.id}">
          <button type="submit">Like</button>
        </form>
        <form method="post" action="/post/reply" class="reply-form">
          <input type="hidden" name="post_id" value="${comment.id}">
          <textarea name="reply_content" placeholder="Répondre..." required></textarea>
          <button type="submit">Répondre</button>
        </form>
        <a href="/post/${comment.id}">Voir le post</a>
      </div>
    </div>
  `;

  const commentsContainer = document.querySelector('.comments');
  commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
}