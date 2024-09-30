document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("create-post-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Empêche le rechargement de la page

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

  // Fonction pour créer un élément DOM pour le nouveau post
  function createPostElement(post) {
    const postDiv = document.createElement("div");
    postDiv.classList.add("post");

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
                <span class="like-count">0 Like(s)</span>
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
