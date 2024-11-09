document.addEventListener("DOMContentLoaded", function () {
  // Gestion du clic sur les posts
  document.querySelectorAll(".post[data-post-id]").forEach(function (post) {
      post.addEventListener("click", function (event) {
          if (event.target.closest(".post-footer")) {
              return;
          }
          var postId = this.getAttribute("data-post-id");
          if (postId) {
              window.location.href = "/post/" + postId;
          }
      });
  });

  // Gestion du clic sur les commentaires
  document.querySelectorAll(".post[data-comment-id]").forEach(function (comment) {
      comment.addEventListener("click", function (event) {
          if (event.target.closest(".post-footer")) {
              return;
          }
          var commentId = this.getAttribute("data-comment-id");
          if (commentId) {
              window.location.href = "/post/" + commentId;
          }
      });
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const scrollUpButton = document.querySelector('.button-scroll-up');
  scrollUpButton.addEventListener('click', function() {
      window.scrollTo({
          top: 0,
          behavior: 'smooth'
      });
  });
});