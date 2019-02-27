<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 27/02/2019
 * Time: 12:51
 */

use mageekguy\atoum\reports\coverage;
use mageekguy\atoum\writers\std;

$script->addDefaultReport();

$coverage = new coverage\html();
$coverage->addWriter(new std\out());
$coverage->setOutPutDirectory(__DIR__ . '/coverage');
$runner->addReport($coverage);
