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
				<h1><a href="<?= \OCFram\RouterFactory::getRouter('Frontend')->getUrl( 'News', 'index' ) ?>">Mon super site</a></h1>
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
					<?php //$AttributMenu_a = [new AttributMenu(['app'=>'Frontend', 'module'=>'Connexion', 'action'=>'index', 'name'=>'Connexion'])]; //TODO: le mettre ailleur + création auto
					foreach ( $List_bouton_a as $Bouton_a ): ; ?>
						
						<li><a href="<?= \OCFram\RouterFactory::getRouter($Bouton_a['app'])->getUrl($Bouton_a['module'], $Bouton_a['action'] ) ?>"><?= $Bouton_a['name']?></a></li>
					<?php endforeach; ?>
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
		<script src="/js/jquery-3.2.1.min.js"></script>
		<script src="/js/script.js"></script>
	</body>
</html>