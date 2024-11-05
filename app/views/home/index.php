<!-- Titre -->
<div class="scroll-up">
    <a href="#top">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z" />
        </svg>
    </a>
</div>

<!-- Afficher les posts -->
<!-- False pour ne pas re-afficher le header et le footer dans la vue en double-->
<?php view(view: 'post/posts', data: ['posts' => $posts], layout: false); ?>

<!-- Liens vers des fichiers JavaScript -->
<script src='js/scripts.js'></script>