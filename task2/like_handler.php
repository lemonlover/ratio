<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	CModule::IncludeModule("iblock");
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
		$request = CIBlockElement::GetProperty(
		  $_REQUEST["iblock_id"],
		  $_REQUEST["article_id"],
		  array(),
		  array("CODE"=>"USER_LIKES")
		);
		while ($result = $request->GetNext()) {
			$user_like_ids[] = $result["VALUE"];
		}
	
		if(in_array($_REQUEST["user_id"], $user_like_ids)){
			foreach ($user_like_ids as $key => $value) {
				if($_REQUEST["user_id"] == $value) {
					unset($user_like_ids[$key]);
					reset($user_like_ids);
				}
			}
			$hide = true;				
		}
		else { $hide = false; $user_like_ids[] = $_REQUEST["user_id"];
			   $login = $USER->GetLogin(); 
		}
		CIBlockElement::SetPropertyValuesEx($_REQUEST["article_id"], false, array("USER_LIKES" =>$user_like_ids));
		
		if($hide)
		{	
			$response["remove"] = true;
			$response["user_id"] = $_REQUEST["user_id"];
		}
		else {
			$response["add"] = true;
			$response["user_id"] = $_REQUEST["user_id"];
			$response["login"] = $login; 
		}
		echo json_encode($response);
		die($content);
	}

	else {die();}

?>