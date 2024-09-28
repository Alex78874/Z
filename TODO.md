# Todo

- [X] Restructuré la base de donnée plus simplement
  - [X] Faire en sorte que un commentaire soit lié a son post mais aussi au post parent
- [ ] Connexion de l'utilisateur
- [ ] Page d'Accueil
  - [ ] Affichage des info utilisateur sur la page d'accueil
  - [ ] Affichage des posts sur la page d'accueil
  - [ ] Listener AJAX pour ajout des post en temps réel sur la page d'accueil
- [ ] Page Post
  - [ ] Récupérer count like et count de commentaire et les affiché sur les icones
  - [ ] Ajout de commentaire a un post
    - [ ] Model Commentaire seulement pour ajout (récupération dans Model Post)
    - [ ] Affichage Commentaire (Post)
    - [ ] Créer Model Like et LikeController pour pouvoir liker des post et commentaire

## Notes

<https://codepen.io/jouanmarcel/pen/RwweKqb>

- Considéré un commentaire de post comme un autre post, et pouvoir retrouvé le parent le plus haut de ce post
