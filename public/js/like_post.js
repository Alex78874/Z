document.addEventListener('DOMContentLoaded', function() {
  const likeButton = document.getElementById('likeButton');
  const postId = document.getElementById('postId').value;

  likeButton.addEventListener('click', function(event) {
    event.preventDefault();

    const data = {
      postId: postId
    };

    fetch('/post/like', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json;charset=UTF-8'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        // Handle successful like (e.g., update the UI)
        alert('Post liked successfully!');
      } else {
        // Handle error
        alert('Error liking post: ' + response.message);
      }
    })
    .catch(error => {
      // Handle network errors
      alert('Network error: ' + error.message);
    });
  });
});
