<fieldset data-id="<?=$comment['id']?>" data-action="Comment">
	<legend data-action = "mouse-over">
		Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
		<?php if ($user->isAdmin() OR ($user->isAuthenticated() AND $user->login() == $comment['auteur'])) { ?> -
			<a data-action="edit-comment" data-id= "<?=$comment['id']?>" href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'updateComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Modifier</a> |
			<a data-action="remove-comment" data-id= "<?=$comment['id']?>"  href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteCommentJson', [ 'id' => $comment[ 'id' ] ], 'json' ) ?>">Supprimer</a>
		<?php } ?>
	</legend>
	<br class="comment-content"  >
		<?php //TODO test curl une seule fois
		$Content= preg_replace("(\r\n|\n|\r)",' ',$comment['contenu']); //remplacesaut de lignes... etc par des ' '
		$Content_a = explode(' ', $Content);
		require_once 'C:\Users\gcottel\Desktop\UwAmp\www\formation\vendor\curl\curl\src\Curl\Curl.php';
		foreach ( $Content_a as $content )
		{
			$content = trim($content); // supprime les ' '
			
			if (preg_match("#^https://www.youtube.com/watch\?v=#", $content) AND filter_var($content, FILTER_VALIDATE_URL)) // reconnais url youtube
			{?>
				<a href=<?=$content?>>Lien Youtube</a>
				<br>
				<object width="425" height="344">
					<param name="movie" value="http://www.youtube.com/v/<?=substr($content, 32)?>"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<embed src="http://www.youtube.com/v/<?=htmlspecialchars(substr($content, 32))?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed>
				</object>
				<br>
				
			
			<?php $pattern = '#'.$content.'#';
				$pattern = preg_replace('#\?#', '\\\?', $pattern);
				$comment['contenu'] = preg_replace($pattern,'[Lien youtube ci-dessus]',$comment['contenu']);

			}
			
		}?>
	
		<?= nl2br(htmlspecialchars($comment['contenu'])) ?>
	</p>
</fieldset>

