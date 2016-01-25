<?
	$fetched_users = array();
	foreach ($arResult["ITEMS"] as $key => $value) {
		$USER_LIKES = $value["DISPLAY_PROPERTIES"]["USER_LIKES"]["VALUE"]; 
		if(!empty($USER_LIKES)){
			$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["USER_LIKES"]["DISPLAY_VALUE"]  = array();
			foreach ($USER_LIKES as $k => $user) {
				if(!in_array($user, array_keys($fetched_users))){				
					$arfilter["ID"] = $user;
					$select = array("FIELDS"=>array("LOGIN", "ID"));
					$by="id";
					$order="desc";
					$request = CUser::GetList($by,$order,$arfilter,$select);
					if ($result = $request->GetNext()) {
						$fetched_users[$result["ID"]] = $result["LOGIN"];
						$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["USER_LIKES"]["DISPLAY_VALUE"][$result["ID"]] = $result["LOGIN"]; 
					}
				} 
				else {
					$arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"]["USER_LIKES"]["DISPLAY_VALUE"][$user] = $fetched_users[$user];
				}
			}
		}
	}


	/*echo "<pre>"; print_r($arResult); echo "</pre>";*/


?>