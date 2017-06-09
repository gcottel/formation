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

<input type="submit" value="Voir plus" data-action = "voir-plus" data-id= "<?=$news['id']?>" />


<form action="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertCommentJson', [ 'news' => $news[ 'id' ] ], 'json' ) ?>" method="post" id="commentform2">
	<p>
		<?= $form ?>
		<input type="submit" value="Commenter" />
	</p>
</form>
<script>
	var _url_to_remove_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteCommentJson', [], 'json' ) ?>";
	var _url_to_update_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'UpdateCommentJson', [], 'json' ) ?>";
	var _url_to_show_more_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'ShowMoreJson', [], 'json' ) ?>";
	var _url_to_refresh_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'RefreshJson', [], 'json' ) ?>";
	var _url_to_insert_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertCommentJson', [ 'news' => $news[ 'id' ] ], 'json' ) ?>";
	var _url_to_qTip_comment = "<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'qTipCommentJson', [], 'json' ) ?>";
</script>

