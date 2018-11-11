<?php

	$source_html="source.html";
	echo get_metas_keywords_description($source_html);

	function get_metas_keywords_description($source_html){
		//les metas  keywords +description
		$tab_metas=get_meta_tags($source_html);
		return $tab_metas["keywords"]." ".$tab_metas["description"];
	}
?>