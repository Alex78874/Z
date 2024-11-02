document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-post').forEach(function (button) {
        button.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            console.log('postId :', postId);

            if (confirm('Voulez-vous vraiment supprimer ce post ?')) {
                fetch('post/delete/' + postId, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json().catch(() => {
                        throw new Error('Invalid JSON response');
                    });
                })
                .then(data => {
                    if (data.success) {
                        // Supprimer l'élément du DOM
                        const postElement = document.querySelector(`.post[data-post-id='${postId}']`);
                        if (postElement) {
                            postElement.remove();
                        }
                    } else {
                        alert('Erreur : ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    alert('Une erreur est survenue.');
                });
            }
        });
    });
});