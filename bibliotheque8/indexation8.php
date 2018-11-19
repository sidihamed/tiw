<?php 

	include '../bib_funs.inc.php';

	$tab_head = $arrayName = array('php' => 2, 'java' => 3, 'web' => 1);
	$tab_body = $arrayName = array('poo' => 20, 'xml' => 12, 'java' => 1, 'php' => 20, 'python' =>1);
	$tab_mots_occ = $tab_body;
	if (sizeof($tab_head) > sizeof($tab_body)) $tab_mots_occ = $tab_head;
	
	$coeff = 1.5;
	$tab_mots_poids_head = poid($tab_head, $coeff);

	$tab_mots_poids = fusion_deux_tableaux ($tab_mots_poids_head, $tab_body);
	print_r($tab_mots_poids);
		
 ?>