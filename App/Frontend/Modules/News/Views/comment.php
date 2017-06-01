<fieldset data-id="<?=$comment['id']?>" data-action="Comment">
	<legend>
		Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
		<?php if ($user->isAdmin() OR ($user->isAuthenticated() AND $user->login() == $comment['auteur'])) { ?> -
			<a data-action="edit-comment" data-id= "<?=$comment['id']?>" data-contenu = "<?=$comment['contenu']?>" data-auteur = "<?=$comment['auteur']?>" href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'updateComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Modifier</a> |
			<a data-action="remove-comment" data-id= "<?=$comment['id']?>"  href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteCommentJson', [ 'id' => $comment[ 'id' ] ], 'json' ) ?>">Supprimer</a>
		<?php } ?>
	</legend>
	<p class="comment-content"  ><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
</fieldset>

