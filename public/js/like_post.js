document.addEventListener('DOMContentLoaded', function() {
  const likeForms = document.querySelectorAll('.like-form');

  likeForms.forEach(form => {
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      const postElement = form.closest('.post') || form.closest('.comment');
      const post_id = postElement.getAttribute('data-post-id') || postElement.getAttribute('data-comment-id');

      fetch('/post/like/', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          post_id: post_id
        })
      })
      .then(response => {
        console.log(response);
        if (response.headers.get('content-type')?.includes('application/json')) {
          return response.json();
        } else {
          throw new Error('Unexpected content type');
        }
      })
      .then(response => {
        if (response.success) {
          const likeCountSpan = postElement.querySelector('.like-count');
          likeCountSpan.textContent = `${response.likeCount} Like(s)`;
        } else {
          alert('Error liking post: ' + response.message);
        }
      })
      .catch(error => {
        alert('Network error: ' + error.message);
        console.error('Network error:', error);
      });
    });
  });
});
