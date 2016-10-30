<?php
	$current_page = isset($_GET['p']) ? $_GET['p'] : null;
		
	redirect($current_page);
	
	if($current_page=='inventory' || $current_page=='sell')
	{
		$character = isset($_GET['character']) ? $_GET['character'] : null;
		if($character)
		{
			if(!character_check($character))
				header("Location: index.php?p=characters");
		}
		else header("Location: index.php?p=characters");
	}
	
	if($current_page=='sell')
	{
		$pos = isset($_GET['i']) ? $_GET['i'] : null;
		$item = array();
		$item = items_select($character, $pos);
		if(!isset($item[0]['pos']))
			header("Location: index.php?p=inventory&character=".$character);
	}
	
	license();
	
	if($current_page=='item')
	{
		$vnum = isset($_GET['id']) ? $_GET['id'] : null;
		$item_name = get_item_name($vnum);
		$lvl = get_item_lvl($vnum);
		
		$check = array();
		$check = check_item_available_market($vnum);
		if(!isset($check[0]['id']))
			header("Location: index.php");	
	}
	if($current_page=='buy')
	{
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$item = array();
		$item = items_select_market($id);
		if(!isset($item[0]['id']))
			header("Location: index.php");
	}

?>