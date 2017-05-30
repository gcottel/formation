<fieldset>
	<legend>
		Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
		<?php if ($user->isAuthenticated()) { ?> -
			<a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'updateComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Modifier</a> |
			<a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Supprimer</a>
		<?php } ?>
	</legend>
	<p class="comment content"><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
</fieldset>