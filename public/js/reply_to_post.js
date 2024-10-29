document.addEventListener('DOMContentLoaded', function() {
  const replyForm = document.getElementById('replyForm');
  const replyButton = document.getElementById('replyButton');
  const replyContent = document.getElementById('replyContent');
  const postId = document.getElementById('postId').value;

  replyButton.addEventListener('click', function(event) {
    event.preventDefault();

    const data = {
      postId: postId,
      content: replyContent.value
    };

    fetch('/post/reply', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json;charset=UTF-8'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
      if (response.success) {
        // Handle successful reply creation (e.g., update the UI)
        alert('Reply posted successfully!');
      } else {
        // Handle error
        alert('Error posting reply: ' + response.message);
      }
    })
    .catch(error => {
      // Handle network errors
      alert('Network error: ' + error.message);
    });
  });
});