import { handleLike, addLikeEventListeners } from './like_post.js';

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("create-post-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    const content = formData.get("content").trim();
    const fileInput = form.querySelector('input[name="attachment"]');
    const file = fileInput.files[0];
    console.log(file);

    if (content === "") {
      alert("Le contenu du post ne peut pas être vide.");
      return;
    }

    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = new Image();
        img.src = e.target.result;

        img.onload = function () {
          console.log("aaaa");
          const canvas = document.createElement("canvas");
          const ctx = canvas.getContext("2d");
          canvas.width = img.width;
          canvas.height = img.height;
          ctx.drawImage(img, 0, 0);

          canvas.toBlob(function (blob) {
            formData.set("attachment", blob, "image.webp");
            console.log(formData);
            sendPostData(formData);
          }, "image/webp");
        };
      };
      reader.readAsDataURL(file);
    } else {
      sendPostData(formData);
    }
  });

  function sendPostData(formData) {
    fetch("post", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors de la requête");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          console.log(data);
          form.reset();
          // Ajouter le nouveau post en haut de la liste des posts
          const postsContainer = document.querySelector(".posts-container");
          const newPost = createPostElement(data.post);
          postsContainer.insertAdjacentElement("afterbegin", newPost);
          addLikeEventListeners(newPost);
        } else {
          alert(data.message || "Erreur lors de la création du post.");
        }
      })
      .catch((error) => {
        console.log(data);
        console.error("Erreur:", error);
      });
  }

  // Intervalle pour vérifier les nouveaux posts toutes les 5 secondes
  setInterval(fetchNewPosts, 5000);

  function fetchNewPosts() {
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
            if (!document.querySelector(`.post[data-post-id="${post.id}"]`)) {
              const newPost = createPostElement(post);
              postsContainer.insertAdjacentElement("afterbegin", newPost);
              addEventListeners(newPost);
              // console.log("New post added:", post);
            } else {
              // console.log("Post already exists:", post.id);
            }
          });
        } else {
          // console.log("No new posts found or fetch unsuccessful.");
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
        <hr class="post-separator">

        <div class="post-header">
            <div class="post-user">
                <img class="post-avatar" src="${escapeHtml(
                  post.user_avatar
                )}" alt="Avatar de l'utilisateur">
                <strong>${escapeHtml(post.username)}</strong>
                <span class="post-date">${escapeHtml(
                  post.publication_date
                )}</span>
            </div>
        </div>
        <div class="post-content">
            <p>${escapeHtml(post.content)}</p>
            ${
              post.attachment
                ? `<img class="post-attachment" src="${escapeHtml(
                    post.attachment
                  )}" alt="Image attachée au post">`
                : ""
            }
        </div>
        <div class="post-footer">
            <div class="post-like">
                <button class="like-button" data-post-id="${escapeHtml(
                  post.id
                )}" data-liked="${post.liked ? "yes" : "no"}">
                    ${
                      post.liked
                        ? `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                        </svg>
                    `
                        : `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                        </svg>
                    `
                    }
                </button>
                <span class="like-count">${escapeHtml(post.like_count)}</span>
            </div>
            <div class="post-comment-count">
                <button class="comment-button" data-post-id="${escapeHtml(
                  post.id
                )}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>
                <span class="comment-count">${escapeHtml(
                  post.comment_count
                )}</span>
            </div>
            ${
              post.isAdmin
                ? `
                <button class="btn-delete-post" data-post-id="${escapeHtml(
                  post.id
                )}">Supprimer le post</button>
            `
                : ""
            }
        </div>
    `;
    return postDiv;
  }

  // Ajouter des écouteurs d'événements aux boutons de like et de commentaire existants
  document.querySelectorAll('.post').forEach(addLikeEventListeners);

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
