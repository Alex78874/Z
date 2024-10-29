document.addEventListener('DOMContentLoaded', function() {
  const likeForms = document.querySelectorAll('.like-form');

  likeForms.forEach(form => {
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      const postElement = form.closest('.post');
      const post_id = postElement.getAttribute('data-post-id');
      const data = {
        post_id: post_id // Ensure this matches the PHP code
      };
      console.log('Data:', JSON.stringify(data));

      fetch('/post/like', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json;charset=UTF-8'
        },
        body: JSON.stringify(data),
      })
      .then(response => {
        if (response.headers.get('content-type')?.includes('application/json')) {
          return response.json();
        } else {
          throw new Error('Unexpected content type');
        }
      })
      .then(response => {
        if (response.success) {
          // Handle successful like (e.g., update the UI)
          alert('Post liked successfully!');
          const likeCountSpan = postElement.querySelector('.like-count');
          likeCountSpan.textContent = `${response.likeCount} Like(s)`;
        } else {
          // Handle error
          alert('Error liking post: ' + response.message);
        }
      })
      .catch(error => {
        // Handle network errors
        alert('Network error: ' + error.message);
        console.error('Network error:', error);
      });
    });
  });
});
