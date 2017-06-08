<fieldset data-id="<?=$comment['id']?>" data-action="Comment">
	<legend>
		Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
		<?php if ($user->isAdmin() OR ($user->isAuthenticated() AND $user->login() == $comment['auteur'])) { ?> -
			<a data-action="edit-comment" data-id= "<?=$comment['id']?>" href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'updateComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Modifier</a> |
			<a data-action="remove-comment" data-id= "<?=$comment['id']?>"  href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteCommentJson', [ 'id' => $comment[ 'id' ] ], 'json' ) ?>">Supprimer</a>
		<?php } ?>
	</legend>
	<br class="comment-content"  ><?= nl2br(htmlspecialchars($comment['contenu'])) ?>
		<br>
		<?php
		if (preg_match("#^https://www.youtube.com/watch\?v=#", $comment['contenu']))
		{?>
			<object width="425" height="344">
				<param name="movie" value="http://www.youtube.com/v/<?=substr($comment['contenu'], 32)?>"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<embed src="http://www.youtube.com/v/<?=substr($comment['contenu'], 32)?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed>
			</object>
			
		<?php }
		?>
	</p>
</fieldset>

