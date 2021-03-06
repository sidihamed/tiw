<?php 

include '../bib_funs.inc.php';

$source_html ="source.html";

indexer9($source_html);

function indexer9($source)
{
    global $separateurs;

    //--------------------------1 : traitement de head ---------------------//
  
    $title = strtolower(get_title ($source));
    $keywords = strtolower(get_metas_keywords($source));
    $description = strtolower(get_metas_description($source));

    //unification du texte à traiter
    $texte_head = ($title." ".$keywords." ".$description);

    //traduction des entités html en ascii
    $texte_head = entitiesHTML2ASCII($texte_head);

    //tokénisation du head
    $tab_mots_head = explode_bis($separateurs, $texte_head);

    $tab_head = array_count_values($tab_mots_head);

    //------------------------  2 : traitement body --------------------------------------//

    //récupération du body
    $texte_body = strtolower(get_body($source));

    //traduction des entités html en ascii
    $texte_body = entitiesHTML2ASCII($texte_body);
     
    //tokénisation du body
    $tab_mots_body = explode_bis($separateurs, $texte_body);

    $tab_body= array_count_values($tab_mots_body);

    //------------------------------------------------------------------------------------//

    $coeff = 1.5;

    $tab_head_poids = poids($tab_head,$coeff);

    $tab_mots_poids_doc = fusion_deux_tableaux($tab_head_poids,$tab_body);

    print_r($tab_mots_poids_doc);

    //insert_bdd($tab_mots_poids_doc,$source);
}

?>