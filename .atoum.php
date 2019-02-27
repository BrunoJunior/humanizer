<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 27/02/2019
 * Time: 12:51
 */

$script->addDefaultReport();

$cloverWriter = new mageekguy\atoum\writers\file(__DIR__.'/build/logs/clover.xml');
$cloverReport = new mageekguy\atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);
$runner->addReport($cloverReport);
