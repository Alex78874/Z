document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche la soumission normale du formulaire

        // Récupérer les données du formulaire
        const formData = new FormData(form);

        // Envoyer les données via fetch API
        fetch('/register', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête Ajax
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Inscription réussie, rediriger ou mettre à jour l'interface
                alert('Inscription réussie !');
                window.location.href = '/dashboard'; // Par exemple
            } else {
                // Afficher les erreurs
                const errorDiv = document.getElementById('errorMessages');
                errorDiv.innerHTML = data.errors.join('<br>');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
});
