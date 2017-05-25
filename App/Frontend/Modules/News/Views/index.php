<?php
foreach ($listeNews as $news)
{
    ?>
    <h2><a href="<?= \OCFram\RouterFactory::getRouter('Frontend')->getUrl( 'News', 'show',['id'=>$news['id']])?>"><?= htmlentities( $news[ 'titre' ] ) ?></a></h2>
    <p><?= nl2br(htmlspecialchars($news['contenu'])) ?></p>
    <?php
}