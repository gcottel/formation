<?php
$FrontendRouter = \OCFram\RouterFactory::getRouter('Frontend');
$BackendRouter = \OCFram\RouterFactory::getRouter('Backend');
?>


<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8" />

    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
	<link rel="stylesheet" href="/css/style.css" type="text/css" />
</head>

<body>
<div id="wrap">
    <header>
		<h1><a href="<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ) ?>">Mon super site</a></h1>
        <p>Comment ça, il n'y a presque rien ?</p>
    </header>

    <nav>
        <ul>
            <?php if ($user->isAuthenticated()) { ?>
                <li><a href="<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ) ?>">Admin</a></li>
				<li><a href="<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'insert' ) ?>">Ajouter une news</a></li>
                <li> <a href="<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'Connexion', 'logOut' ) ?>">LogOut</a></li>
                <li> <a href="<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getUrl( 'Connexion', 'insert' ) ?>">SignIn</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>
</body>
</html>