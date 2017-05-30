<p>Par <em><?= $news[ 'auteur' ] ?></em>, le <?= $news[ 'dateAjout' ]->format( 'd/m/Y à H\hi' ) ?></p>
<h2><?= htmlspecialchars( $news[ 'titre' ] ) ?></h2>
<p><?= nl2br( htmlspecialchars( $news[ 'contenu' ] ) ) ?></p>

<?php if ( $news[ 'dateAjout' ] != $news[ 'dateModif' ] ) { ?>
	<p style="text-align: right;">
		<small><em>Modifiée le <?= $news[ 'dateModif' ]->format( 'd/m/Y à H\hi' ) ?></em></small>
	</p>
<?php }

if ( empty( $comments ) ) {
	?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
	<?php
} ?>


<form action="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertCommentJson', [ 'news' => $news[ 'id' ] ], 'json' ) ?>" method="post" id="commentform1">
	<p>
		<?= $form ?>
		<input type="submit" value="Commenter" />
	</p>
</form>

<div id="commentList">
	<?php foreach ( $comments as $comment ): ?>
		<?php require 'comment.php'; ?>
	<?php endforeach; ?>
</div>

<form action="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertCommentJson', [ 'news' => $news[ 'id' ] ], 'json' ) ?>" method="post" id="commentform2">
	<p>
		<?= $form ?>
		<input type="submit" value="Commenter" />
	</p>
</form>

