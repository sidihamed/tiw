<?php

$separateurs = " ,.():!?»«\t\"\n\r\'-+/*%{}[]#0123456789 '’;&@";

$tab_mots_vides = file('mots-vides.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

function insertion_BDD($source_html, $titre, $descriptif, $tab_mots_poids_doc){
    
    $connexion = mysqli_connect("localhost","root","","tiw");
    $idMot = 0; $idSource = 0;
    $select_document = "SELECT * FROM document WHERE source = '".$source_html."' and titre = '".$titre."' ";
    $resultats_select_document = mysqli_query($connexion,$select_document);
    
    //Ajouter un document à la base de données
    if (mysqli_num_rows($resultats_select_document)==0) {

       $insert_document = " insert into document(source,titre,descriptif) values ('$source_html','$titre','$descriptif') ";

       $resultats_insert_document = mysqli_query($connexion,$insert_document);        
           if ($resultats_insert_document) {
               echo "Le document:  ".$source_html."  a été ajouté<br><br>";
               $idSource = mysqli_insert_id($connexion);
           }
           else{
              echo "Erreur d'insertion dans la BDD <br><br>";
           }
    }
    else{     
       $idSource = mysqli_fetch_row($resultats_select_document)[0];
       echo "Le document:   $source_html   existe dans la BDD","<br><br>";
    }

    //Ajouter un mot à la base de données
    foreach ($tab_mots_poids_doc as $mot => $poids) {
       $select_mot = "SELECT * FROM mot WHERE mot = '".$mot."' ";
       $resultats_select_mot = mysqli_query($connexion,$select_mot);
      
       if (mysqli_num_rows($resultats_select_mot)==0) {

           $insert_mot = " insert into mot(mot) values ('$mot') "; 
           $resultats_insert_mot = mysqli_query($connexion,$insert_mot);
           if ($resultats_insert_mot) {
               echo "Le mot:  ".$mot."  a été ajouté<br>";
               $idMot = mysqli_insert_id($connexion);
           }
           else{
               echo "Erreur d'insertion dans la BDD <br>";
           }
       }
       else{     
           $idMot = mysqli_fetch_row($resultats_select_mot)[0];
           echo "Le mot:   $mot   existe dans la BDD","<br>";
        }             
        //insertion dans la table d'association
        $select_mot_document = "SELECT * FROM mot_document WHERE idMot = '".$idMot."' and idSource = '".$idSource."' ";

        $resultats_select_mot_document = mysqli_query($connexion,$select_mot_document);

        if (mysqli_num_rows($resultats_select_mot_document)==0) {

           $sql = "INSERT INTO mot_document (idMot, idSource, poids) VALUES ($idMot, $idSource, $poids)";

           $resultat = mysqli_query($connexion, $sql);

           if ($resultat) {

              echo " Succes d'insertion dans la table d'association <br><br>";
           }
           else{
              echo " Erreur d'insertion dans la table d'association <br><br>";
           }
        }
    }
}

function indexer($source)
{
	global $separateurs;
    global $source_html;

    //-----------------1 : traitement de head ---------------------//
  
    $titre = strtolower(get_title ($source));
    $keywords = strtolower(get_metas_keywords($source));
    $descriptif = strtolower(get_metas_description($source));

    //unification du texte à traiter
    $texte_head = ($titre." ".$keywords." ".$descriptif);

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

    //print_r($tab_mots_poids_doc);

    insertion_BDD($source_html, $titre, $descriptif, $tab_mots_poids_doc);
}


function fusion_deux_tableaux($tab_head,$tab_body){
      
        foreach($tab_head as $mot => $occurence){
            
                if (array_key_exists($mot,$tab_body)) {
                    
                        $tab_body[$mot] += $occurence ;
                }else{
                        $tab_body[$mot] = $occurence ;
                }
            }
            
           return $tab_body;   
}

function poids($tab, $coeff){
    foreach($tab as $key => $value){
        
            $tab[$key] *= $coeff;
        }
    return $tab;     
}

function insert_bdd($tab_mots,$source_html){

    $connexion = mysqli_connect("localhost","root","","tiw");
    
    foreach($tab_mots as $mot => $poids){
    
        $sql = "insert into source_mot_poids(source,mot,poids) 
        values('$source_html','$mot',$poids) ";
    
        $test = mysqli_query($connexion,$sql);
    
        if($test){
            echo $sql, "<br>";
        }else{
            echo "erreur $sql <br>";
        }
    
    }
}

//traduction des caractéres html en ascii
function entitiesHTML2ASCII($chaine)
{
    //HTML_ENTITIES: tous les caractères éligibles en entités HTML.

    // retourne la table de traduction des entités utilisée en interne par la htmlentities():
    $table_caracts_html = get_html_translation_table(HTML_ENTITIES); 

    // retourne un tableau dont les clés sont les valeurs du précédent $table_caracts_html, et les valeurs sont les clés. 
    $tableau_html_caracts =  array_flip ( $table_caracts_html );

    // retourne une chaine de caractères après avoir remplacé les éléments/clés par les éléments/valeurs  du tableau associatif de
    //paires  $tableau_html_caracts dans la chaîne $chaine.
    $chaine  =  strtr ($chaine,   $tableau_html_caracts ); 

    return $chaine;
}

// Extraction du body en texte
function get_body($source_html)
{	
	$chaine_html=implode(file ($source_html),"");

	$modele_balises_scripts = '/<script[^>]*?>.*?<\/script>/is'; 

	$html_sans_script = preg_replace($modele_balises_scripts, '', $chaine_html) ;

	$modele='/<body[^>]*>(.*)<\/body>/si';

	$chaine_texte_body = preg_match($modele, $html_sans_script, $body);

	// Supprime les balise HTML et PHP
    $model = '#<[^>]+>#';
	$chaine_texte_body = preg_replace($model, ' ', $body[1]);

	return $chaine_texte_body;
}

//Extraction des keywords des metas html
function get_metas_keywords($source_html){
	$chaine_metas = "";
	//metas keywords
	$tab_metas=get_meta_tags($source_html);
	if (isset($tab_metas["keywords"])) 
        $chaine_metas .= $tab_metas["keywords"];
	return strtolower($chaine_metas);
}
//Extraction de la description des metas html
function get_metas_description($source_html){
    $chaine_metas = "";
    //metas description
    $tab_metas=get_meta_tags($source_html);
    if (isset($tab_metas["description"])) 
        $chaine_metas .= " " .$tab_metas["description"];
    return strtolower($chaine_metas);
}

// Extraction de title html
function get_title($source_html){
    $chaine_html=implode(file ($source_html),"");
	$modele='/<title>(.*)<\/title>/si';
	//preg_match  elle cherche modele dans chaine_html est elle va le mettre dans titre[1], titre[0] contient la chaine avec le modele
	if (preg_match($modele, $chaine_html, $titre))
		return strtolower($titre[1]);
	else return "Sans Titre\n";
}

// Tokenization de la chaine en mot
function explode_bis($separateur,$chaine){
	global $tab_mots_vides;
    $tab = array();

	$tok =strtok($chaine ,$separateur);
	if (strlen($tok) > 2 and !(in_array($tok, $tab_mots_vides)))
        $tab[] = $tok;

	while ($tok !== false)
	{
	    $tok = strtok($separateur);
	    if ( strlen($tok) >2 and !(in_array($tok, $tab_mots_vides))) 
            $tab[] = $tok;     
	} 
    return $tab;
}

// Affichage d'un tableau avec indices et valeurs
function print_tab($tab){
    foreach($tab as $indice => $mot ) echo $indice, ":" ,$mot ,"<br />";
}
?>