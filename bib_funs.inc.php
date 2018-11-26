<?php


function indexer($source)
{
	$separateurs = " ,.():!?»«\t\"\n\r\'-+/*%{}[]#0123456789 '’;&@";

    //--------------------------1 : traitement de head------------------------------------//

    $key_desc = get_metas_keywords_description($source);

    //récupération du titre
    $title = get_title ($source);

    //unification du texte à traiter
    $texte_head = strtolower($key_desc. " " .$title);

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

    $tab_head_poid = poid($tab_head,$coeff);

    $tab_mots_poids_doc = fusion_deux_tableaux($tab_head_poid,$tab_body);

    //print_r($tab_mots_poids_doc);

    insert_bdd($tab_mots_poids_doc,$source);
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

function poid($tab, $coeff){
    foreach($tab as $key => $value){
        
            $tab[$key] *= $coeff;
        }

    return $tab;     
}

function insert_bdd($tab_mots,$source_html){

    $connexion = mysqli_connect("localhost","root","","tiw");
    
    foreach($tab_mots as $mot => $poid){
    
        $sql = "insert into source_mot_poid(source,mot,poid) 
        values('$source_html','$mot',$poid) ";
    
        $test = mysqli_query($connexion,$sql);
    
        if($test){
            echo $sql, "<br>";
        }else{
            echo "erreur $sql <br>";
        }
    
    }
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
		$chaine_texte_body = strip_tags($body[1]);

		return $chaine_texte_body;
	}

	//Extraction des keywords et description des metas html
	function get_metas_keywords_description($source_html){
		//les metas  keywords + description
		$tab_metas=get_meta_tags($source_html);
		return $tab_metas["keywords"]." ".$tab_metas["description"];
	}

	// Extraction de title html
	function get_title($source_html){
	    $chaine_html=implode(file ($source_html),"");
		$modele='/<title>(.*)<\/title>/si';
		//preg_match  elle cherche modele dans chaine_html est elle va le mettre dans titre
		preg_match($modele, $chaine_html, $titre);
		return $titre[1] ;
	}

	// Tokenization de la chaine en mot
	function explode_bis($separateur,$chaine){
		$tab = array();
		$tok =strtok($chaine ,$separateur);
		
		if (strlen($tok) > 2) $tab[] =$tok;
		while ($tok !== false)
		{
		    $tok = strtok($separateur);
		    if ( strlen($tok) >2) $tab[] = $tok;     
		} 
		return $tab;
	}

	// Affichage d'un tableau avec indices et valeurs
	function print_tab($tab){
	    foreach($tab as $indice => $mot ) echo $indice, ":" ,$mot ,"<br />";
	}
?>