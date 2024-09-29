<!-- Titre -->
<link rel="stylesheet" href="<?php echo url(path: 'css/home.css'); ?>">
<h1>
    <?php if (!empty($title)) {
        echo htmlspecialchars(string: $title);
    } ?>
</h1>

<!-- Afficher les posts -->
<!-- False pour ne pas re-afficher le header et le footer dans la vue en double-->
<?php view(view: 'post/posts', data: ['posts' => $posts], layout: false); ?>

<!-- Liens vers des fichiers JavaScript -->
<script src="<?php echo url(path: 'js/scripts.js'); ?>"></script>