<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
    <tr><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>

	<?php
	foreach ($listeNews as $news): ?>
	
		<tr>
		<td><?= htmlspecialchars($news['titre']) ?></td>
			<td>le <?= htmlspecialchars($news['dateAjout']->format('d/m/Y à H\hi')) ?></td>
				<td><?= htmlspecialchars(($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi'))) ?></td>
				<td>
					<a href="<?= \OCFram\RouterFactory::getRouter('Frontend')->getUrl( 'News', 'update',['id'=>$news['id']])?>"><img src="/images/update.png" alt="Modifier" /></a>
					<a href="<?= \OCFram\RouterFactory::getRouter('Frontend')->getUrl('News', 'delete',['id'=>$news['id']])?>"><img src="/images/delete.png" alt="Supprimer" /></a>
				</td>
			</tr> <?php "\n";
    endforeach;
	?>
</table>