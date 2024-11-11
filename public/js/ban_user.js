document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-ban-user').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            const userId = this.getAttribute('data-user-id');

            if (confirm('Voulez-vous vraiment bannir cet utilisateur ?')) {
                fetch('user/ban/' + userId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Utilisateur banni avec succès.');
                        // Optionnel : supprimer les posts de l'utilisateur banni du DOM
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