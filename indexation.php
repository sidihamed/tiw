
<?php

 /* Découpage d'une chaine de caractére avec la fonction explode 
 qui prend 2 parametres: le séperateur et la chaine à découper.*/

$texte = "Bonjour tout le monde.  L'université de Paris8, est ouverte toute l'année";
$tab_mots = explode(" " , $texte);
foreach ($tab_mots  as  $mot )  echo $mot, "<br />";

?>