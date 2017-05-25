<p>Par <em><?= $news['auteur'] ?></em>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2><?= htmlspecialchars($news['titre']) ?></h2>
<p><?= nl2br(htmlspecialchars($news['contenu'])) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
    <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<p><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertComment', [ 'news' => $news[ 'id' ] ] ) ?>">Ajouter un commentaire</a></p>



<script language="javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		// Au submit du formulaire
		$('#form').submit( function() {
			var pseudo = $('#pseudo').val();
			var commentaire = $('#contenu').val();
			// On supprime les anciens messages d'erreurs ou de succès
			$('.erreur').remove();
			$('.ok').remove();
			// Si on a commentaire à ajouter
			if (pseudo != '' && commentaire != '') {
				// On compte le nombre de commentaire déjà présent + celui qui va être créé
				var nbCom=1;
				$('#commentaire p').each(function() { nbCom++; });
				// On enlève la class "last" à l'ancien dernier
				$('.last').removeClass('last');
				// On ajoute le nouvel élément
				$('#commentaire').prepend('<p class="last" id="com_'+nbCom+'"><strong>'+pseudo+'</strong> a dit :<br />'+commentaire+'</p>');
				// On le met pair ou impair
				if ($('.last').next().is('.pair'))
					$('.last').addClass('impair');
				else
					$('.last').addClass('pair');
				if (!$('.last').next().is('p'))
					$('.last').addClass('first');
				// On efface le contenu du formulaire
				$('#contenu').val('').focus();
				$('#contenu').after('<span class="ok">Commentaire ajouté avec succès</span>');
				$('.ok').hide().fadeIn('slow');
			}
			else {
				if (pseudo == '')
					$('#pseudo').after('<span class="erreur">Champ requis</span>');
				if (commentaire == '')
					$('#contenu').after('<span class="erreur">Champ requis</span>');
				$('.erreur').hide().fadeIn('slow');
			}
			// On retourne false pour ne pas recharger la page
			return false;
		});
	});
</script>

<form action="jquery.html" method="post" id="form">
	<fieldset>
		<legend>Ajouter un commentaire</legend>
		<label for="pseudo">Pseudo</label>
		<input type="text" name="pseudo" id="pseudo" />
		<br />
		<label for="contenu">Commentaire</label>
		<textarea name="contenu" id="contenu" rows="4" cols="4"></textarea>
		<br />
		<input type="submit" value="Ok" />
	</fieldset>
</form>

<div id="commentaire">
	<p class="last pair first" id="com_1"><strong>BN</strong> a dit :<br />Coucou !  Je m'appelle BN et je suis un BN, et toi, tu fais quoi dans la vie ?</p>
</div>




<?php




if (empty($comments))
{
    ?>
    <p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
    <?php
}

foreach ($comments as $comment)
{
    ?>
    <fieldset>
        <legend>
            Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
            <?php if ($user->isAuthenticated()) { ?> -
				<a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'updateComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Modifier</a> |
				<a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'DeleteComment', [ 'id' => $comment[ 'id' ] ] ) ?>">Supprimer</a>
            <?php } ?>
        </legend>
        <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
    </fieldset>
    <?php
}
?>

<p><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insertComment', [ 'news' => $news[ 'id' ] ] ) ?>">Ajouter un commentaire</a></p>