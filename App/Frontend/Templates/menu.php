<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 07/06/2017
 * Time: 11:38
 */?>

<li><a href="/">Accueil</a></li>
					
<?php if (!$user->isAuthenticated()) { ?>
	<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'Connexion', 'insert' ) ?>">SignIn</a></li>
	<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'Connexion', 'index' ) ?>">Connexion</a></li>
<?php } ?>

<?php if ($user->isAuthenticated()) { ?>
	<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'IndexMyNews' ) ?>">MyNews</a></li>
	<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'insert' ) ?>">Ajouter une news</a></li>
	<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Frontend' )->getUrl( 'Connexion', 'logOut' ) ?>">LogOut</a></li>
<?php } ?>