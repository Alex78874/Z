document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("create-post-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    const content = formData.get("content").trim();

    if (content === "") {
      alert("Le contenu du post ne peut pas être vide.");
      return;
    }

    fetch("post", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest", // Indique que c'est une requête AJAX
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors de la requête");
        }
        return response.json(); // Récupérer la réponse directement en JSON
      })
      .then((data) => {
        if (data.success) {
          // Réinitialiser le formulaire
          form.reset();

          // Ajouter le nouveau post en haut de la liste des posts
          const postsContainer = document.querySelector(".posts-container");
          const newPost = createPostElement(data.post);
          postsContainer.insertAdjacentElement("afterbegin", newPost);
        } else {
          alert(data.message || "Erreur lors de la création du post.");
        }
      })
      .catch((error) => {
        console.error("Erreur:", error);
      });
  });

  // Intervalle pour vérifier les nouveaux posts toutes les 5 secondes
  setInterval(fetchNewPosts, 5000);

  function fetchNewPosts() {
    // console.log("Fetching new posts...");
    const postsContainer = document.querySelector(".posts-container");
    const firstPost = postsContainer.querySelector(".post");
    const lastPostId = firstPost ? firstPost.getAttribute("data-post-id") : 0;

    fetch("post?last_post_id=" + lastPostId, {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data.success && data.posts.length > 0) {
          data.posts.forEach((post) => {
            // Vérifiez si le post est déjà dans le DOM
            if (!document.querySelector(`.post[data-post-id="${post.id}"]`)) {
              const newPost = createPostElement(post);
              postsContainer.insertAdjacentElement("afterbegin", newPost);
              console.log("New post added:", post); // Debugging
            } else {
              console.log("Post already exists:", post.id); // Debugging
            }
          });
        } else {
          console.log("No new posts found or fetch unsuccessful."); // Debugging
        }
      })
      .catch((error) => {
        console.error(
          "Erreur lors de la récupération des nouveaux posts:",
          error
        );
      });
  }

  // Fonction pour créer un élément DOM pour le nouveau post
  function createPostElement(post) {
    const postDiv = document.createElement("div");
    postDiv.classList.add("post");
    postDiv.setAttribute("data-post-id", post.id);

    postDiv.innerHTML = `
            <div class="post-header">
                <div class="post-user">
                    <strong>${escapeHtml(post.username)}</strong>
                    <span class="post-date">${escapeHtml(
                      post.publication_date
                    )}</span>
                </div>
            </div>
            <div class="post-content">
                <p>${escapeHtml(post.content)}</p>
            </div>
            <div class="post-footer">
                <span class="like-count">${escapeHtml(post.like_count)} Like(s)</span>
                <span class="comment-count">${escapeHtml(post.comment_count)} Commentaire(s)</span>
                <!-- Formulaire pour liker un post -->
                <form method="post" action="/post/like" class="like-form">
                    <input type="hidden" name="post_id" value="${escapeHtml(
                      post.id
                    )}">
                    <button type="submit">Like</button>
                </form>
                <!-- Bouton pour répondre à un post -->
                <form method="post" action="/post/reply" class="reply-form">
                    <input type="hidden" name="post_id" value="${escapeHtml(
                      post.id
                    )}">
                    <textarea name="reply_content" placeholder="Répondre..." required></textarea>
                    <button type="submit">Répondre</button>
                </form>
            </div>
            <hr>
        `;
    return postDiv;
  }

  // Fonction pour échapper les caractères spéciaux
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
});
