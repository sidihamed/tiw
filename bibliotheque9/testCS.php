<?php

include '../bib_funs.inc.php';


$chaine = indexer("source.html");

$v = entitiesHTML2ASCII($chaine);

print_r($chaine);

?>
