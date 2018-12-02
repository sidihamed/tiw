<?php

	//inclusion de fonctions prédefinies
	include '../bib_funs.inc.php';

	//séparateur tokenisation
	global $separateurs;

	//fichier html à traiter
	$source_html = "../source.html";

	//récuperation de keywords et descriptif
	$keywords_description = get_metas_keywords_description($source_html);

	//récuperation de titre
	$titre = get_title($source_html);

	//unification des chaines à traiter 
	$texte_head =$keywords_description .  " " . $titre;

	//tokenisation des données head
	$tab_mots = explode_bis($separateurs,$texte_head); 

	/* affichage du titre
	print ("Titre: ".$titre. "<br/>"); */
	
	//affichage des resultats de traitement
	print_tab($tab_mots);

?>