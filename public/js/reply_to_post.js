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
          alert('Erreur : ' + response.message);
        }
      })
      .catch(error => {
        alert('Network error : ' + error.message);
      });
    });
  });
});

function addCommentToUI(comment) {
  const commentDiv = document.createElement("div");
  commentDiv.classList.add("post");
  commentDiv.setAttribute("data-post-id", comment.id);

  commentDiv.innerHTML = `
      <hr class="post-separator">
      <div class="post-header">
          <div class="post-user">
              <img class="post-avatar" src="${escapeHtml("../" + comment.user_avatar)}" alt="Avatar de l'utilisateur">
              <strong>${escapeHtml(comment.username)}</strong>
              <span class="post-date">${escapeHtml(comment.publication_date)}</span>
          </div>
      </div>
      <div class="post-content">
          <p>${escapeHtml(comment.content)}</p>
          ${
              comment.attachment
                  ? `<img class="post-attachment" src="${escapeHtml(comment.attachment)}" alt="Image attachée au commentaire">`
                  : ""
          }
      </div>
      <div class="post-footer">
          <div class="post-like">
              <button class="like-button" data-post-id="${escapeHtml(comment.id)}" data-liked="${comment.liked ? "yes" : "no"}">
                  ${
                      comment.liked
                          ? `
                          <!-- Icône cœur rempli -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                          </svg>
                          `
                          : `
                          <!-- Icône cœur vide -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                        </svg>
                          `
                  }
              </button>
              <span class="like-count">${escapeHtml(comment.like_count)}</span>
          </div>
          <div class="post-comment-count">
              <button class="comment-button" data-post-id="${escapeHtml(comment.id)}">
                  <!-- Icône de commentaire -->
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                    </svg>
              </button>
              <span class="comment-count">${escapeHtml(comment.comment_count)}</span>
          </div>
      </div>
  `;

  const commentsContainer = document.querySelector('.comments');
  commentsContainer.insertAdjacentElement('afterbegin', commentDiv);
}

function escapeHtml(text) {
  if (typeof text !== "string") {
      return text;
  }

  const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, function (m) {
      return map[m];
  });
}