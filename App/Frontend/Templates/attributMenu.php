<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 07/06/2017
 * Time: 11:38
 */?>


<li><a href="<?= \OCFram\RouterFactory::getRouter( htmlspecialchars($attributMenu['app']))->getUrl( htmlspecialchars($attributMenu['module']), htmlspecialchars($attributMenu['action']) ) ?>"><?= htmlspecialchars($attributMenu['name'])?></a></li>


