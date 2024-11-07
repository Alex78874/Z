document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".post").forEach(function (post) {
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