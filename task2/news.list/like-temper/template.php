<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
			<?else:?>
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					/>
			<?endif;?>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
			<?else:?>
				<b><?echo $arItem["NAME"]?></b><br />
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<? $user_id = $USER->GetID();?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<? if ($arProperty["CODE"]=="USER_LIKES") 
					$user_likes = true; 
			   else $user_likes = false;
			   
			   if(in_array( $user_id , array_keys($arProperty["DISPLAY_VALUE"])))
			  		$cur_user_like = true;
			   else $cur_user_like = false;
			?>
			<small>
			<div <?if($user_likes):?>style="display:inline"<?endif;?> >	
			<?=$arProperty["NAME"]?>:&nbsp;</div>
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<? if ($user_likes):?>
					<div class="likers" style="display:inline">
					<?foreach ($arProperty["DISPLAY_VALUE"] as $id=>$login):?>
						<div style="display: inline" class="user" id="u<?=$id?>"><?=$login."&nbsp;/"?></div>
					<?endforeach;?>
					&nbsp;&nbsp;
					<a href="#" class="ajax-like" data-iblock-id="<?=$arResult["ID"]?>"
												  data-article-id="<?=$arItem['ID']?>" 
												  data-user-id="<?=$user_id?>"
												  data-handler-url="<?=SITE_TEMPLATE_PATH."/php/handlers/like_handler.php"?>">
						<?if($cur_user_like):?>
							<?=GetMessage("DONT_LIKE")?>
						<?else:?>
							<?=GetMessage("LIKE")?>
						<?endif;?>
					</a>
					</div>
				<?else:?>
					<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
				<?endif;?>

			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>

<script type="text/javascript">
	$(document).on("click", ".ajax-like",function (e){
		var self = this;
		e.preventDefault(); 
		$.ajax(  { method: "POST",
				  url: $(this).data("handler-url"),
				  data: { iblock_id: $(this).data("iblock-id"), 
				  		  article_id: $(this).data("article-id"), 
				  		  user_id: $(this).data("user-id")} 
		}).done(function(result) {
			var res = $.parseJSON(result)
			 
			if(res["remove"]){
				$(self).closest(".likers").find("#u"+res["user_id"]).remove();
				console.log($(self).closest(".likers"));
				$(self).text("Мне нравится статья");

			}
			else {
				var new_item = $("<div>").attr({id:"u"+res["user_id"], "style":"display:inline-block"});
				new_item.text(res["login"]);
				$(self).closest(".likers")
					   .find(".user:last")
					   .after(new_item);
				$(self).text("Мне не нравится статья");

			}
		});
			 


	});
</script>>