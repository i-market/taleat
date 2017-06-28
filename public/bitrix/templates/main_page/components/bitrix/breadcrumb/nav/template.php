<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '<div class="userbar">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{

	if($index > 0)
		$strReturn .= '<span > / </span>';
			$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if($index==count($arResult)-1)
	{$strReturn .= '<span style="color:#000;">'.$title.'<span>';}
	else{



	if($arResult[$index]["LINK"] <> "")
		$strReturn .= '<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a>';
	else
		$strReturn .= '<span style="color:#000;">'.$title.'<span>';
		
	}	
}

$strReturn .= '</div>';
return $strReturn;
?>
