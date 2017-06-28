<?
function pr($pr){
	?><pre><?print_r($pr);?></pre><?

}



function fn_ReplaceForm($n, $form1, $form2, $form5) {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}


function fn_getCurrSectionID($IBLOCK_ID,$path){
	$arFoundSection=array();

	$arPathSections=preg_split('#/#',$path);
	foreach($arPathSections as $arrkey => $value){
		if (strlen($value)<1){
			unset($arPathSections[$arrkey]);
		}
	}
	$arPathSections=array_reverse($arPathSections);
	
	$found=false;
	
	foreach($arPathSections as $sectioncode){
    	$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID,  'CODE'=>$sectioncode);
    	$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
   		if ($db_list->SelectedRowsCount()>0){
   			$arSection=$db_list->GetNext();
   			$CompiledPath=fn_get_chainpath($arSection['IBLOCK_ID'],$arSection['ID']);
   			if ($CompiledPath==$path) {
   				$arFoundSection=$arSection;
   				break;
   			}
   		}		
	}	
	return $arFoundSection;
}

function fn_getCurrElementID($IBLOCK_ID,$path, $IDSection){
	$IDSection=intval($IDSection);
	
	if (preg_match('#\.html#',$path)){
		$arPathSections=preg_split('#/#',$path);
		foreach($arPathSections as $arrkey => $value){
			if (strlen($value)<1){
				unset($arPathSections[$arrkey]);
			}
		}		
		$arTemp=preg_split('#\.#',array_pop($arPathSections));
		
		if (strlen($arTemp[0])>0){
			$arSelect = Array("ID","IBLOCK_ID");
			$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$arTemp[0]);
			if ($IDSection>0){
				$arFilter['SECTION_ID']=$IDSection;
			}
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
			if ($res->SelectedRowsCount()>0){
				$arElement = $res->GetNext();
				return $arElement;			
			}
		}
	}	
}

function fn_setCatalogApplicationPath($IBLOCK_ID){
	global $APPLICATION;
	$arElement=fn_getCurrElementID($IBLOCK_ID,$APPLICATION->GetCurPage(),$arSection['ID']);

	if ($arElement['ID']>0){
		$APPLICATION->sDocPath2 = '/catalog/'.$arElement['ID'].'.html';
	} else {
		$arSection=fn_getCurrSectionID($IBLOCK_ID,$APPLICATION->GetCurDir());
		if ($arSection['ID']>0){
			$APPLICATION->sDocPath2 = '/catalog/'.$arSection['ID'].'/';	
			$APPLICATION->sDocPath2 .= 'index.php';
		}	 			
	}
}

/*catalog sef end*/

function fn_setpage_el($iblock, $idsection, $idelement)
{


$sec="";
$nav = CIBlockSection::GetNavChain($iblock,$idsection);

while($arChain=$nav->GetNext())
  {
	
	$sec=$sec." - ".$arChain["NAME"];
	  }
	

if($idelement)
{
$arSelect = Array("NAME");
$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$idelement);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$ob = $res->GetNextElement();
$arFields = $ob->GetFields();
$arFields["NAME"]=" - ".$arFields["NAME"];
} 
	
	
return $sec.$arFields["NAME"];



}



?>
<?
 CModule::IncludeModule('iblock');
 CModule::IncludeModule('sale');
 
$arMonth[1]="Январь"; 
$arMonth[2]="Февраль"; 
$arMonth[3]="Март"; 
$arMonth[4]="Апрель"; 
$arMonth[5]="Май"; 
$arMonth[6]="Июнь"; 
$arMonth[7]="Июль"; 
$arMonth[8]="Август"; 
$arMonth[9]="Сентябрь"; 
$arMonth[10]="Октябрь"; 
$arMonth[11]="Ноябрь"; 
$arMonth[12]="Декабрь";  

function unescape($source='') {  
       return preg_replace('#%u([0-9A-F]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))',$source);  
}  
 
 function fn_getSectionCode($temp_code)
 {
  $section_code='';
 
  global $APPLICATION;

  if (strlen(htmlspecialchars($temp_code))>0)
  {
   $section_code=$APPLICATION->GetCurDir();
  
   $arSCode=split ("/",$section_code);
  
   if (count($arSCode)>1)
   {
    $section_code='';
    for ($i=count($arSCode); $i>0;$i--)
    {
     if (strlen($section_code)<1)
     {
      $section_code=$arSCode[$i-1];
     } 
    } 
   }
  }
  return $section_code;
 }

function fn_get_count_section()
{
  CModule::IncludeModule('iblock');
  $path = $arIBlock["LIST_PAGE_URL"];
  
 return $path;
}

function fn_set_newslist_section_path($iblock)
{
 global $APPLICATION;
 $arIBLOCK=GetIBlock($iblock);
 if (strlen($arIBLOCK["LIST_PAGE_URL"])>0)
 {
  if (preg_match('#'.$arIBLOCK["LIST_PAGE_URL"].'#',$APPLICATION->GetCurDir()))
  {
	$section_code=fn_getSectionCode('1');
    $arFilter = Array('IBLOCK_ID'=>$arIBlock["ID"],  'CODE'=>$section_code);
    $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
    if ($db_list->SelectedRowsCount()>0)
    {
     $arSection = $db_list->GetNext();  
     fn_set_iblocknavchain($arSection['IBLOCK_ID'],$arSection['IBLOCK_TYPE_ID'],$arSection['ID'],'');
    } 
  }
 }
}

function fn_get_chainpath($iblock,$idsection)
 {
  CModule::IncludeModule('iblock');
  $arIBlock = GetIBlock($iblock);
  $path = $arIBlock["LIST_PAGE_URL"];

  $nav = CIBlockSection::GetNavChain($iblock,$idsection);
  while($arChain=$nav->GetNext())
  {
   $path.=$arChain["CODE"]."/";
  }
  return $path;
 }
 
 function fn_set_iblocknavchain($iblock,$iblocktype,$idsection,$idelement)
 {
     CModule::IncludeModule('iblock');
     global $APPLICATION;
     
     $arSect=GetIBlockSection($idsection,$iblocktype);
     
	 $nav = CIBlockSection::GetNavChain($iblock,$idsection);
	 $title=' / ';
	 while($arChain=$nav->GetNext())
	 {
	  if (strlen($path)<1) {$path=$arChain["LIST_PAGE_URL"];}
 	  $path.=$arChain["CODE"]."/";
 	  $title.=$arChain["NAME"]." / ";
	  $APPLICATION->AddChainItem($arChain["NAME"],$path); 
	 }
     $APPLICATION->SetPageProperty("title", $APPLICATION->GetProperty('title').$title);	  
 }

function rus2translit($string)  
{  
    $converter = array(  
        'à' => 'a',   'á' => 'b',   'â' => 'v',  
        'ã' => 'g',   'ä' => 'd',   'å' => 'e',  
        '¸' => 'e',   'æ' => 'zh',  'ç' => 'z',  
        'è' => 'i',   'é' => 'y',   'ê' => 'k',  
        'ë' => 'l',   'ì' => 'm',   'í' => 'n',  
        'î' => 'o',   'ï' => 'p',   'ð' => 'r',  
        'ñ' => 's',   'ò' => 't',   'ó' => 'u',  
        'ô' => 'f',   'õ' => 'h',   'ö' => 'c',  
        '÷' => 'ch',  'ø' => 'sh',  'ù' => 'sch',  
        'ü' => "",  'û' => 'y',   'ú' => "",  
        'ý' => 'e',   'þ' => 'yu',  'ÿ' => 'ya',  
  
        'À' => 'A',   'Á' => 'B',   'Â' => 'V',  
        'Ã' => 'G',   'Ä' => 'D',   'Å' => 'E',  
        '¨' => 'E',   'Æ' => 'Zh',  'Ç' => 'Z',  
        'È' => 'I',   'É' => 'Y',   'Ê' => 'K',  
        'Ë' => 'L',   'Ì' => 'M',   'Í' => 'N',  
        'Î' => 'O',   'Ï' => 'P',   'Ð' => 'R',  
        'Ñ' => 'S',   'Ò' => 'T',   'Ó' => 'U',  
        'Ô' => 'F',   'Õ' => 'H',   'Ö' => 'C',  
        '×' => 'Ch',  'Ø' => 'Sh',  'Ù' => 'Sch',  
        'Ü' => "",  'Û' => 'Y',   'Ú' => "",  
        'Ý' => 'E',   'Þ' => 'Yu',  'ß' => 'Ya',  
        ' '=> '-', '(' => '', '.' => '', ','=>'', ')'=>'', ':'=>'', "'"=>'', '%'=>'', '/'=>'', '"'=>'', '¹' => 'N'
        
    );  
    
    $str=strtr($string, $converter);
    
    $str=strtolower($str);
    
    return $str;  
} 

 function fn_ico_file($filename)
 {
  $arFName=preg_split('#\.#',$filename);

  $path='/bitrix/templates/main_page/images/files/';
  $img='';
  switch($arFName[count($arFName)-1])
  {
   	case 'pdf':
   		$img='<img class="ico_ext" width="16" height="16" src="'.$path.'ico_pdf.png">';
   		break;
   	case 'doc': case 'docx': case 'rtf':
   		$img='<img class="ico_ext" width="16" height="16" src="'.$path.'ico_word.png">';
   		break;
   	case 'xls': case 'xlsx':
   		$img='<img class="ico_ext" width="16" height="16" src="'.$path.'ico_xls.png">';
   		break;
   	case 'rar': case 'zip': case '7z': case 'arj':
   		$img='<img class="ico_ext" width="16" height="16" src="'.$path.'ico_arc.png">';
   		break;
   	case 'jpg': case 'gif': case 'png': case 'bmp':
   		$img='<img class="ico_ext" width="16" height="16" src="'.$path.'ico_image.png">';
   		break;
      	
  }
 
  return $img;
 }

function fn_modyfy_cart() {
 $addproduct=true;

 if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
  if ((intval($_REQUEST['idoffer'])>0) && (intval($_REQUEST['item_count'])>0)) {
   $arSelect = Array("IBLOCK_ID", "ID", "NAME","PROPERTY_*");
   $arFilter = Array("ID"=>intval($_REQUEST['idoffer']), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
   $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);  
   if ($res->SelectedRowsCount()>0) {
    $ob=$res->GetNextElement();
    $arProduct=$ob->GetFields();
    $arProductProps=$ob->GetProperties();
    
    $arMainProduct=GetIBlockElement($arProductProps['CML2_LINK']['VALUE']);
    
	$db_price_res = CPrice::GetList(
    	array("PRICE"=>"ASC"),
    	array("PRODUCT_ID" => $arProduct['ID'], "CAN_ACCESS" => "Y", "CAN_BUY"=>"Y")
    );
	if ($arPrice = $db_price_res->Fetch()) {
	
		$arProps = array();

		$arProps[] = array(
    			"NAME" => "Вес",
    			"CODE" => "weight",
    			"VALUE" => $arProductProps['weight']['VALUE']
		);
	
	if (intval($_REQUEST['bonus_group'])>0) {	
	
		$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
		$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>intval($_REQUEST['bonus_group']));
		$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
		if ($res->SelectedRowsCount()>0) {
			$ob=$res->GetNextElement();
			$arBonusFields=$ob->GetFields();
			$arBonusProperties=$ob->GetProperties();
		

			$arProps[] = array(
    			"NAME" => "Бонус",
    			"CODE" => "bonus",
    			"VALUE" => $arProduct['NAME']
			);	

			$arProps[] = array(
    			"NAME" => "Бонусная программа",
    			"CODE" => "bonus_prog",
    			"VALUE" => $arBonusFields['ID']
			);	
			
			$arPrice['PRICE']=0;
// delete all programm products
			$addproduct=!fn_delete_program_bonus($arProduct['ID'],$arBonusFields['ID']);		
		}
	}	
	
		if ($addproduct){
	
	 		$arBasketFields = array(
 				"PRODUCT_ID" => $arProduct['ID'],
 				"PRICE" => $arPrice['PRICE'],
 				"CURRENCY" => $arPrice['CURRENCY'],
 				"LID" => SITE_ID,
 				"FUSER_ID"=> CSaleBasket::GetBasketUserID(),
 				"NAME"=> $arMainProduct['NAME'],
 				"QUANTITY" => intval($_REQUEST['item_count']),
 			);
 			$arBasketFields["PROPS"] = $arProps;
			CSaleBasket::Add($arBasketFields);
		}		
	}
   }   
  }
  elseif ((intval($_REQUEST['idoffer'])>0) && (intval($_REQUEST['item_count'])==-1)) {
   CSaleBasket::Delete(intval($_REQUEST['idoffer']));
  }
  fn_check_bonus_programs();
 } 
}

function fn_delete_program_bonus($PRODUCT_ID,$BONUS_PROG_ID) {
	
	$return=false;

	if (CModule::IncludeModule('sale')) {
				$dbBasketItems = CSaleBasket::GetList(
        		array(
                	"NAME" => "ASC",
                	"ID" => "ASC"
            	),
        		array(
                	"FUSER_ID" => CSaleBasket::GetBasketUserID(),
                	"LID" => SITE_ID,
                	"ORDER_ID" => "NULL",
            	),
        		false,
        		false,
        		array("ID", "CALLBACK_FUNC", "MODULE", 
              		"PRODUCT_ID", "QUANTITY", "DELAY", 
              		"CAN_BUY", "PRICE", "WEIGHT")
    			);
    	
				while ($arItems = $dbBasketItems->Fetch()) {
 					$db_res = CSaleBasket::GetPropsList(
        				array(
                			"SORT" => "ASC",
                			"NAME" => "ASC"
            			),
        				array("BASKET_ID" => $arItems['ID'], "CODE"=>'bonus_prog')
    				);
    	
				while ($ar_res = $db_res->Fetch()){
					if ($ar_res['VALUE']==$BONUS_PROG_ID) {
						if ($arItems['PRODUCT_ID']==$PRODUCT_ID) {$return=true;}
						CSaleBasket::Delete($arItems['ID']);							
					}
				}
			}
	}	
	return $return;	
}

function fn_check_bonus_programs() {

	$cart_sum=0;

	$dbBasketItems = CSaleBasket::GetList(
    	array(
          	"NAME" => "ASC",
           	"ID" => "ASC"
       	),
    	array(
           	"FUSER_ID" => CSaleBasket::GetBasketUserID(),
           	"LID" => SITE_ID,
           	"ORDER_ID" => "NULL",
       	),
		false,
    	false,
    	array("ID", "CALLBACK_FUNC", "MODULE", 
         		"PRODUCT_ID", "QUANTITY", "DELAY", 
          		"CAN_BUY", "PRICE", "WEIGHT")
    );
	
	while ($arItems = $dbBasketItems->Fetch()) {
		$cart_sum+=$arItems['QUANTITY']*$arItems['PRICE'];
	}

	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*","PREVIEW_TEXT");
	$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
	if ($res->SelectedRowsCount()>0) {
		while($ob=$res->GetNextElement()) {
			$arBonusFields=$ob->GetFields();
			$arBonusProperties=$ob->GetProperties();	
			
			if ($cart_sum<$arBonusProperties['sum']['VALUE']) {
				fn_delete_program_bonus(0,$arBonusFields['ID']);
			}	
		}
	}

}

function send_mail_auth($to, $thm, $html, $arFile) 
 { 
    $kod = 'utf-8'; 
    $email_from="site@pecorino.ru";
    $from = '=?' . $kod . '?B?' . base64_encode("Сайт пекорино").'?='.' <' . $email_from . '>';
//    $thm='=?' . $kod . '?B?' . base64_encode($thm) . '?=';

//	$thm = 'site order';

include_once("Mail.php"); 
include_once('Mail/mime.php');

$headers=array();

$headers["From"]    = $from; 
$headers["To"]      = $to; 
$headers["Subject"] = $thm; 

$params["host"] = "mail.pizzazdes.ru"; 
$params["port"] = "25"; 
$params["auth"] = true; 
$params["username"] = "site@pizzazdes.ru"; 
$params["password"] = "Ooph5voh"; 

$crlf = "\n";

$mime = new Mail_mime($crlf);

//$mime->setTXTBody($text);
$mime->setHTMLBody($html);

    foreach($arFile as $path)
    { 
     $fp = fopen($path,"r"); 
     if (!$fp) 
     {
      continue; 
     } 
     $file = fread($fp, filesize($path)); 
     fclose($fp);  
 	 $mime->addAttachment($path);
    } 

//do not ever try to call these lines in reverse order
$body = $mime->get(array('html_charset'=>'utf-8','text_charset'=>'utf-8','head_charset'=>'utf-8'));
$hdrs = $mime->headers($headers);

// Create the mail object using the Mail::factory method 
$mail_object =& Mail::factory("smtp", $params); 
$mail_object->send($to, $hdrs, $body); 

  }; 
  
function fn_send_order($ORDER_ID) {

$subject="d_".date('dmYHis')."011";


$filename=$subject.".xml";

$filepath=$_SERVER["DOCUMENT_ROOT"]."/tmp_orders/";

$fh = fopen($filepath.$filename, "w");
	$arOrder = CSaleOrder::GetByID($ORDER_ID);

fwrite($fh, '<?xml version="1.0" encoding="UTF-8"?>'."\r\n");
fwrite($fh, '<order>'."\r\n");

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 2)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<f_name>'.$arVals['VALUE'].'</f_name>'."\r\n");
	  } else {
      	fwrite($fh, '<f_name></f_name>'."\r\n");	  
	  } 

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 7)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<l_name>'.$arVals['VALUE'].'</l_name>'."\r\n");
	  } else {
      	fwrite($fh, '<l_name></l_name>'."\r\n");	  
	  } 

fwrite($fh, '<m_name></m_name>'."\r\n");
fwrite($fh, '<organization></organization>'."\r\n");

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 3)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<phone1>'.$arVals['VALUE'].'</phone1>'."\r\n");
	  } else {
      	fwrite($fh, '<phone1></phone1>'."\r\n");	  
	  } 
	  
fwrite($fh, '<phone2></phone2>'."\r\n");

fwrite($fh, '<town>Omsk</town>'."\r\n");

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 5)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<street>'.$arVals['VALUE'].'</street>'."\r\n");
	  } else {
      	fwrite($fh, '<street></street>'."\r\n");	  
	  } 

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 8)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<house>'.$arVals['VALUE'].'</house>'."\r\n");
	  } else {
      	fwrite($fh, '<house></house>'."\r\n");	  
	  } 

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 9)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<building>'.$arVals['VALUE'].'</building>'."\r\n");
	  } else {
      	fwrite($fh, '<building></building>'."\r\n");	  
	  }
	  
	fwrite($fh, '<entry></entry>'."\r\n");
 
      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 10)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<flat>'.$arVals['VALUE'].'</flat>'."\r\n");
	  } else {
      	fwrite($fh, '<flat></flat>'."\r\n");	  
	  } 


fwrite($fh, '<floor></floor>'."\r\n");
fwrite($fh, '<codeentry></codeentry>'."\r\n");

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 4)
        );
      if ($arVals = $db_vals->Fetch()) {
      	fwrite($fh, '<email>'.$arVals['VALUE'].'</email>'."\r\n");
	  } else {
      	fwrite($fh, '<email></email>'."\r\n");	  
	  } 
	  
fwrite($fh, '<adv_info>pizzazdes.ru</adv_info>'."\r\n");
fwrite($fh, '<advert>pizzazdes.ru</advert>'."\r\n");
fwrite($fh, '<order_summ>'.$arOrder['PRICE'].'</order_summ>'."\r\n");

fwrite($fh, '<d_type>'.(($arOrder['DELIVERY_ID']==2)?'1':'0').'</d_type>'."\r\n");

 $orderDate = new DateTime($arOrder['DATE_INSERT']);
 $orderDate ->modify('+8 hour');// 7 GMT

fwrite($fh, '<wait_time>'.$orderDate -> format('d.m.Y H:i:s').'</wait_time>'."\r\n");
fwrite($fh, '<menu>'."\r\n");

				$dbBasketItems = CSaleBasket::GetList(
						array("NAME" => "ASC"),
						array("ORDER_ID" => $ORDER_ID),
						false,
						false,
						array("ID", "NAME", "QUANTITY","PRICE","PRODUCT_ID")
					);
					
				$i=0;	
				while ($arBasketItems = $dbBasketItems->Fetch())
				{
					$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
					$arFilter = Array("IBLOCK_ID"=>4, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$arBasketItems['PRODUCT_ID']);
					$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
					if ($res->SelectedRowsCount()>0) {
						$ob=$res->GetNextElement();
						$arProps=$ob->GetProperties();
												
						$i++;					
						fwrite($fh, '<itemnum'.$i.'>'."\r\n");	
						fwrite($fh, '<item id="'.$arProps['account_code']['VALUE'].'" name="'.$arBasketItems['NAME'].'" quantity="'.$arBasketItems['QUANTITY'].'" price="'.$arBasketItems['PRICE'].'"/>'."\r\n");	
						fwrite($fh, '</itemnum'.$i.'>'."\r\n");	
					}									
				}
fwrite($fh, '</menu>'."\r\n".'</order>'."\r\n");


$event='SEND_ORDER2RKEEPER';
$arFields['SUBJECT']=$subject;

send_mail_auth('rkeeper@pizzazdes.ru', $arFields['SUBJECT'], '', array($filepath.$filename));      
//send_mail_auth('test@arcline.ru', $arFields['SUBJECT'], 'Заказ покупателя', array($filepath.$filename));      
}  
  

function fn_send_order_to_manager($ORDER_ID) {

	$arOrder = CSaleOrder::GetByID($ORDER_ID);
	$arDelivery=CSaleDelivery::GetByID($arOrder['DELIVERY_ID']);

	$mailbody='';

 	$orderDate = new DateTime($arOrder['DATE_INSERT']);
 	
 	
	$mailbody="<b>Заказ:</b> ".$ORDER_ID."<br>";
	$mailbody.="<b>Дата:</b> ".$orderDate -> format('d.m.Y H:i:s')."<br><br>";

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 2)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Имя заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 7)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Фамилия заказчика:</b> ".$arVals['VALUE']."<br>"; 
  	  }

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 3)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Телефон заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 5)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Улица заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 8)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Дом заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 9)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Строение заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }
	  	  
      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 10)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>Квартира/офис заказчика:</b> ".$arVals['VALUE']."<br>"; 
	  }
	  
	  $mailbody.="<b>Служба доставки:</b> ".$arDelivery['NAME']."<br>";

      $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array("ORDER_ID" => $ORDER_ID, "ORDER_PROPS_ID" => 4)
        );
      if ($arVals = $db_vals->Fetch()) {
      	$mailbody.="<b>E-mail заказчика:</b> ".$arVals['VALUE']."<br><br>"; 
  	  }
      	$mailbody.="<b>Сумма заказа:</b> ".$arOrder['PRICE']."</b><br><br>"; 

      	$mailbody.="<b>Состав заказа:</b><br>";
      	
				$dbBasketItems = CSaleBasket::GetList(
						array("NAME" => "ASC"),
						array("ORDER_ID" => $ORDER_ID),
						false,
						false,
						array("ID", "NAME", "QUANTITY","PRICE")
					);
					
				$i=0;	
				while ($arBasketItems = $dbBasketItems->Fetch())
				{
					$i++;
					
					$mailbody.="<b>".$i.":</b> ".$arBasketItems['NAME']." кол-во ".$arBasketItems['QUANTITY']." цена ".$arBasketItems['PRICE']."<br>";
				}

$arFields['SUBJECT']='New order';

send_mail_auth('order@pizzazdes.ru', $arFields['SUBJECT'], $mailbody, array());      
//send_mail_auth('test@arcline.ru', $arFields['SUBJECT'], $mailbody, array());      
}  
    
  
  
function fn_check_dinnertime() {
	if ((date('N')==6) || (date('N')==7)) {return false;}

	$start_time = mktime(12, 0, 0, date('n'), date('j'), date('Y')); //Omsk time
	$end_time = mktime(15, 0, 0, date('n'), date('j'), date('Y'));
	
	$currtime=mktime(date('G'), date('i'), 0, date('n'), date('j'), date('Y'));
	
	if (($currtime>=$start_time) && ($currtime<=$end_time)) {return true;} else {return false;}
	
	//true - user can order dinner
	
}  

function fn_remove_dinner_products() {
	if (!fn_check_dinnertime()) {
		$id_dinner_section=8;
		
		$dbBasketItems = CSaleBasket::GetList(
    		array(
          		"NAME" => "ASC",
           		"ID" => "ASC"
       		),
    		array(
           		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
           		"LID" => SITE_ID,
           		"ORDER_ID" => "NULL",
       		),
			false,
    		false,
    		array("ID", "CALLBACK_FUNC", "MODULE", 
         		"PRODUCT_ID", "QUANTITY", "DELAY", 
          		"CAN_BUY", "PRICE", "WEIGHT")
    	);
	
		while ($arItems = $dbBasketItems->Fetch()) {
			$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
			$arFilter = Array("IBLOCK_ID"=>4, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$arItems['PRODUCT_ID']);
			$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
			if ($res->SelectedRowsCount()>0) {
				$ob=$res->GetNextElement();
				$arProps=$ob->GetProperties();
							
				$arFilter = Array("IBLOCK_ID"=>3, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "SECTION_ID"=>$id_dinner_section, "INCLUDE_SUBSECTIONS"=>"Y", "ID"=>$arProps['CML2_LINK']['VALUE']);
				$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
				if ($res->SelectedRowsCount()>0) {
					CSaleBasket::Delete($arItems['ID']);
				}
			}
		}		
	}
}

function fn_check_min_order_sum(){
	$minorder=350;
	
	$cart_sum=0;

	$dbBasketItems = CSaleBasket::GetList(
    	array(
          	"NAME" => "ASC",
           	"ID" => "ASC"
       	),
    	array(
           	"FUSER_ID" => CSaleBasket::GetBasketUserID(),
           	"LID" => SITE_ID,
           	"ORDER_ID" => "NULL",
       	),
		false,
    	false,
    	array("ID", "CALLBACK_FUNC", "MODULE", 
         		"PRODUCT_ID", "QUANTITY", "DELAY", 
          		"CAN_BUY", "PRICE", "WEIGHT")
    );
	
	while ($arItems = $dbBasketItems->Fetch()) {
		$cart_sum+=$arItems['QUANTITY']*$arItems['PRICE'];
	}
	
	if (($cart_sum<$minorder) && ($cart_sum>0)) {return false;} else {return true;}	
	
}

function fn_sync_delivery_zones() {

try {
    	$dbh = new PDO("firebird:dbname=92.255.176.238:D:\delivery\BASE\DELIVERY.FDB", "SYSDBA", "masterkey");

/* streets */
    	
    	$sql = "SELECT * FROM DLV_STREETS";
    	
    	foreach ($dbh->query($sql) as $row) {
        	foreach($row as $key => $value) {        		
        		$row[$key]=mb_convert_encoding($value,'UTF8','WINDOWS-1251');
        	}
        	
			$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
			$arFilter = Array("IBLOCK_ID"=>9, "PROPERTY_STREET_ID"=>$row['STREET_ID']);
			$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
			if ($res->SelectedRowsCount()>0) {
				$ob=$res->GetNextElement();
				$arFields=$ob->GetFields();
			}
			
			$el = new CIBlockElement;
			$arElementFields=array();
			
        	$arElementFields['IBLOCK_ID']=9;
        	$arElementFields['NAME']=$row['STREET_NAME'];
        	if ($row['DELETED']==0) {$arElementFields['ACTIVE']='Y';} else {$arElementFields['ACTIVE']='N';}
        	
        	$PROP = array();
        	
        	$PROP['DELETED']=$row['DELETED'];
        	$PROP['STREET_ID']=$row['STREET_ID'];
        	$PROP['TOWN_ID']=$row['TOWN_ID'];
        	$PROP['F_USE']=$row['F_USE'];        	        	        	
        	
			$arElementFields['PROPERTY_VALUES']=$PROP;

			if ($arFields['ID']>0) {
				$el->Update($arFields['ID'],$arElementFields);
			} else {
				$el->Add($arElementFields);
			}
        }


/* zones */

    	$sql = "SELECT * FROM DLV_ZONES";

    	foreach ($dbh->query($sql) as $row) {
    	
        	foreach($row as $key => $value) {        		
        		$row[$key]=mb_convert_encoding($value,'UTF8','WINDOWS-1251');
        	}

  			$arFilter = Array('IBLOCK_ID'=>10, 'UF_ZONE_ID'=>$row['ZONE_ID']);
  			$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, Array("UF_*"));
  			if ($db_list->SelectedRowsCount()>0) {
  				$arSection=$db_list->GetNext();
  			}
  			
			$bs = new CIBlockSection;
			
			$arSectionFields=array();
			
			$arSectionFields['IBLOCK_ID']=10;
			$arSectionFields['NAME']=$row['ZONE_NAME'];		
			$arSectionFields['UF_ZONE_ID']=$row['ZONE_ID'];
			$arSectionFields['UF_DELETED']=$row['DELETED'];		
				
			$arMatches=array();
			
			preg_match('#(\d+)#', $row['ZONE_NAME'], $arMatches);	
				
			$arSectionFields['UF_ZONE_COST']=$arMatches[0];				

			if($arSection['ID'] > 0) {
  				$bs->Update($arSection['ID'], $arSectionFields);
			} else {
  				$bs->Add($arSectionFields);
			}
		}	

/* zone_details */

    	$sql = "SELECT * FROM DLV_ZONE_DETAILS";

    	foreach ($dbh->query($sql) as $row) {
    	
        	foreach($row as $key => $value) {        		
        		$row[$key]=mb_convert_encoding($value,'UTF8','WINDOWS-1251');
        	}
        	
  			$arFilter = Array('IBLOCK_ID'=>10, 'UF_ZONE_ID'=>$row['ZONE_ID']);
  			$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, Array("UF_*"));
  			if ($db_list->SelectedRowsCount()>0) {
  				$arSection=$db_list->GetNext();
  			}
 
        	
			$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
			$arFilter = Array("IBLOCK_ID"=>10, "PROPERTY_ZONE_DETAIL_ID"=>$row['ZONE_DETAIL_ID']);
			$res = CIBlockElement::GetList(Array("SORT"=>"ASC","NAME"=>"ASC"), $arFilter, false, false, $arSelect);
			if ($res->SelectedRowsCount()>0) {
				$ob=$res->GetNextElement();
				$arFields=$ob->GetFields();
			}
			
			$el = new CIBlockElement;
			$arElementFields=array();
			
        	$arElementFields['IBLOCK_ID']=10;
        	$arElementFields['NAME']=$row['ZONE_DETAIL_ID'];
        	$arElementFields['IBLOCK_SECTION_ID']=$arSection['ID'];
        	
        	if ($row['DELETED']==0) {$arElementFields['ACTIVE']='Y';} else {$arElementFields['ACTIVE']='N';}
        	
        	$PROP = array();
        	
        	$PROP['ZONE_DETAIL_ID']=$row['ZONE_DETAIL_ID'];
        	$PROP['DELETED']=$row['DELETED'];
        	$PROP['STREET_ID']=$row['STREET_ID'];
        	$PROP['HOUSE_ORDER']=$row['HOUSE_ORDER'];
        	$PROP['MIN_HOUSE']=$row['MIN_HOUSE'];        	        	        	
        	$PROP['MAX_HOUSE']=$row['MAX_HOUSE'];     
        	
			$arElementFields['PROPERTY_VALUES']=$PROP;

			if ($arFields['ID']>0) {
				$el->Update($arFields['ID'],$arElementFields);
			} else {
				$el->Add($arElementFields);
			}
		}	



    /*** close the database connection ***/
    $dbh = null;
    
    }   
catch (PDOException $e)
    {
    echo $e->getMessage();
    }
}



function fn_getBestPrice($IDOffer,$arPriceNames,$isWithoutDiscount=false,$getMaxPrice=false,&$arPriceOffer){
  if (CModule::IncludeModule('catalog')){
   $arMatrix=CatalogGetPriceTable($IDOffer);
   $offerprice=-1;

   foreach($arMatrix['COLS'] as $pricetypekey => $arPriceType){ 

    if ((count($arPriceNames)>0) && (!in_array($arPriceType['NAME_LANG'],$arPriceNames))) {continue;}

    $pricekey='PRICE';
    if (!$isWithoutDiscount){
     if (array_key_exists('DISCOUNT_PRICE',$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']])){
      $pricekey='DISCOUNT_PRICE';
     }
    }

    if (!$getMaxPrice) {
     if (($offerprice>$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']][$pricekey]) || ($offerprice==-1)){
      $offerprice=$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']][$pricekey];
      $arPriceOffer=$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']];
     }     
    } else {
     if (($offerprice<$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']][$pricekey]) || ($offerprice==-1)){
      $offerprice=$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']][$pricekey];
      $arPriceOffer=$arMatrix['MATRIX'][0]['PRICE'][$arPriceType['ID']];
     }         
    } 
   }
   return  $offerprice;
  }
 }


function send_mail($to, $thm, $html, $path, $name_f) 

  { 

    $fp = fopen($path,"r"); 

    if (!$fp) 

    { 

      print "Файл $path не может быть прочитан"; 

      exit(); 

    } 

    $file = fread($fp, filesize($path)); 

    fclose($fp); 

    

    $boundary = "--".md5(uniqid(time())); // генерируем разделитель 

    $headers .= "MIME-Version: 1.0\n"; 

    $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n"; 

    $multipart .= "--$boundary\n"; 

    $kod = 'koi8-r'; // или $kod = 'windows-1251'; 

    $multipart .= "Content-Type: text/html; charset=$kod\n"; 

    $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n"; 

    $multipart .= "$html\n\n"; 



    $message_part = "--$boundary\n"; 

    $message_part .= "Content-Type: application/octet-stream\n"; 

    $message_part .= "Content-Transfer-Encoding: base64\n"; 

    $message_part .= "Content-Disposition: attachment; filename = \"".$name_f."\"\n\n"; 

    $message_part .= chunk_split(base64_encode($file))."\n"; 

    $multipart .= $message_part."--$boundary--\n"; 



    if(!mail($to, $thm, $multipart, $headers)) 

    { 

      echo "К сожалению, письмо не отправлено"; 

      exit(); 

    } 

  }?>