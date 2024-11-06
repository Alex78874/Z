document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".like-button").forEach(function (button) {
    button.addEventListener("click", function () {
      const postElement = this.closest(".post");
      const post_id =
        postElement.getAttribute("data-post-id") ||
        postElement.getAttribute("data-comment-id");

      fetch("/post/like/", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          post_id: post_id,
        }),
      })
        .then((response) => {
          console.log(response);
          if (
            response.headers.get("content-type")?.includes("application/json")
          ) {
            return response.json();
          } else {
            throw new Error("Unexpected content type");
          }
        })
        .then((data) => {
          if (data.success) {
            // Mettre à jour l'attribut data-liked
            this.setAttribute("data-liked", data.liked ? "yes" : "no");
            // Mettre à jour l'icône du bouton
            this.innerHTML = data.liked
              ? `
                  <!-- Icone coeur rempli -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                  </svg>
                `
              : `
                  <!-- Icone coeur vide -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                  </svg>
                `;
            // Mettre à jour le compteur de likes
            const likeCountElement = this.nextElementSibling;
            likeCountElement.textContent = `${data.likeCount} Like(s)`;
          } else {
            alert("Error liking post: " + data.message);
          }
        })
        .catch((error) => {
          alert("Network error: " + error.message);
          console.error("Network error:", error);
        });
    });
  });
});
