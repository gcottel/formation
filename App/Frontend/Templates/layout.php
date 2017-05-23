<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8" />

    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/">Mon super site</a></h1>
        <p>Comment ça, il n'y a presque rien ?
        <br>
        <?php
        require_once 'C:\Users\gcottel\Desktop\UwAmp\www\formation\vendor\mobiledetect\mobiledetectlib\Mobile_Detect.php';
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        echo ('Vous utilisez un '.$deviceType);
        ?></p>


    </header>

    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>

            <?php if (!$user->isAuthenticated()) { ?>
                <li> <a href="/signIn.html">SignIn</a></li>
                <li> <a href="/connexion.html">Connexion</a></li>
            <?php } ?>
            <?php if ($user->isAuthenticated()) { ?>
                <li> <a href="/myNews">MyNews</a></li>
                <li><a href="/news-insert.html">Ajouter une news</a></li>
                <li> <a href="/logOut">LogOut</a></li>
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