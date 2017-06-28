<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

global $USER;
$datetime2 = date_create(date("Y-m-d"));
$res = CUser::GetList($o, $b, array("ID_EQUAL_EXACT" => 1), array("SELECT"=>array("UF_HOLYDAY","UF_HOLYDAY_TO")));

if ($ob = $res->Fetch()){
	if ($ob["UF_HOLYDAY"]){
		$datetime1 = date_create(date("Y-m-d", MakeTimeStamp($ob["UF_HOLYDAY_TO"], "DD.MM.YYYY")));
		$interval = date_diff($datetime2,$datetime1);

		$days = $interval->format('%r%a');
		if ($days<1){
			$oUser = new CUser;
			$aFields = array("UF_HOLYDAY" => array(0), "UF_HOLYDAY_FROM" => array(""), "UF_HOLYDAY_TO" => array(""));
			$oUser->Update(1, $aFields);
		}
	}
}