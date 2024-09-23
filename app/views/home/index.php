<h1><?php echo $title; ?></h1>

<!-- Afficher les tweets -->
<?php if (!empty($tweets)): ?>
    <?php foreach ($tweets as $tweet): ?>
        <div class="tweet">
            <p><?php echo htmlspecialchars($tweet['content']); ?></p>
            <small>Posté par <?php echo htmlspecialchars($tweet['author']); ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun tweet à afficher.</p>∑
<?php endif; ?>
