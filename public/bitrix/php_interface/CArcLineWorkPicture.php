<?
//V1.1 - Element and Section image optimization

class CArcLineWorkPicture
{
  private $arTmpPictures=array();	
  private $arWaterMarkSettings=array();
  public $arIBlockPictOpt=array();
  public $arPicOptValues=array();
  public $arIBlockSectionPictOpt=array();

  function __construct(&$arPicOptValues,&$arIBlockPictOpt, $arIBlockSectionPictOpt) 
  {
//   $this->arWaterMarkSettings[1]=array('WIDTH','599','/bitrix/templates/main/images/WM_600px.png');  
   
   $this->arIBlockPictOpt=$arIBlockPictOpt;
   $this->arPicOptValues=$arPicOptValues;
   $this->arIBlockSectionPictOpt=$arIBlockSectionPictOpt;
   
  }

  function __destruct() 
  {
   $this->arTmpPictures = array();
  }

  function fn_resizepicture(&$arFields,$arOriginalPicture,$OPT_PROPERTY,$count, $isSection)
  {
	$arPicOptUpdate=array();
	$optpiccount=$count*count($this->arPicOptValues);

	if ($isSection) {
		if ($arOriginalPicture['del']==1) {
	 	$this->fn_deletesectionpictures($arFields,$arOriginalPicture,$OPT_PROPERTY,$count);
	 	return;
		}
	} else {
		if ($arOriginalPicture['del']=='Y') {
	 	$this->fn_deletepictures($arFields,$arOriginalPicture,$OPT_PROPERTY,$count);
	 	return;
		}
	}	
	
	if (strlen($arOriginalPicture['tmp_name'])<1) {return;}

	if (!$isSection){
		$res = CIBlockProperty::GetByID($OPT_PROPERTY);
		if(!$arOptProperty = $res->GetNext()){return;}
	}

	if ($arFields['ID']>0)
	{
	     $arPicProps=array();
	     if ($isSection) {
//make $arPics array	     
			$arFilter=array("IBLOCK_ID"=>$arFields['IBLOCK_ID'], "ID"=>$arFields['ID']);
	     	$res = CIBlockSection::GetList(Array(), $arFilter, false,Array("UF_OPT_MORE_PHOTO"));
	     	if ($res->SelectedRowsCount()>0){
	  			$arSectionPics=$res->GetNext();   	
	     	}
	     } else {
		 	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "PROPERTY_*");
		 	$arFilter = Array("IBLOCK_ID"=>$arFields['IBLOCK_ID'], "ID"=>$arFields['ID']);
		 	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		 	if ($res->SelectedRowsCount()>0){
		  		$ob=$res->GetNextElement();
		  		$arPicFields = $ob->GetFields();   
		  		$arPicProps = $ob->GetProperties(); 
		 	}	
		 }
	}
	
	foreach($this->arPicOptValues as $optkey => $arValue)
	{
		copy($arOriginalPicture["tmp_name"],$arOriginalPicture["tmp_name"]."_".$this->arPicOptValues[$optkey][0]);
		$this->arTmpPictures[]=$arOriginalPicture["tmp_name"]."_".$this->arPicOptValues[$optkey][0];

		$arPicOptUpdate[$optkey]=CFile::MakeFileArray($arOriginalPicture["tmp_name"]."_".$this->arPicOptValues[$optkey][0]);
		$arPicOptUpdate[$optkey]['name']=$arOriginalPicture["name"];
   	
		$arPicOptUpdate[$optkey] = CIBlock::ResizePicture($arPicOptUpdate[$optkey], array(
		 			$this->arPicOptValues[$optkey][1] => $this->arPicOptValues[$optkey][2],
					"METHOD" => "resample",
					"COMPRESSION" => 100
				));
//watermark if needed 
//		$this->fn_make_watermark($arPicOptUpdate[$optkey]);
 	}
		
	for($i=0;$i<count($this->arPicOptValues);$i++)
	{
			$prop_key='n'.$optpiccount;
			if ($isSection) {
	   			$arFields[$this->arIBlockSectionPictOpt[$arFields['IBLOCK_ID']][1]][$optpiccount]=$arPicOptUpdate[$i];			
			} else {
	   			if (array_key_exists($optpiccount,$arPicProps[$arOptProperty['CODE']]['PROPERTY_VALUE_ID'])) 
	   				{$prop_key=$arPicProps[$arOptProperty['CODE']]['PROPERTY_VALUE_ID'][$optpiccount];}
	   			$arFields["PROPERTY_VALUES"][$OPT_PROPERTY][$prop_key]=$arPicOptUpdate[$i];
			}
	   		$optpiccount++;

	}
	 	
  }
  
  function fn_delete_temp_picture()
  {		
		foreach ($this->arTmpPictures as $key => $filename)
		{
		 	unlink($filename);		 		
		}
  }
  
  function fn_deletepictures(&$arFields,$arOriginalPicture,$OPT_PROPERTY,$count)
  {  
	$arPicOptUpdate=array();
	$optpiccount=$count*count($this->arPicOptValues);
	  
	$res = CIBlockProperty::GetByID($OPT_PROPERTY);
	if(!$arOptProperty = $res->GetNext()){return;}  

	if ($arFields['ID']>0)
	{
	     $arPicProps=array();
		 $arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "PROPERTY_*");
		 $arFilter = Array("IBLOCK_ID"=>$arFields['IBLOCK_ID'], "ID"=>$arFields['ID']);
		 $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		 if ($res->SelectedRowsCount()>0)
		 {
		  $ob=$res->GetNextElement();
		  $arPicFields = $ob->GetFields();   
		  $arPicProps = $ob->GetProperties(); 
		 }
		 
		for($i=0;$i<count($this->arPicOptValues);$i++)
		{
	   		if (array_key_exists($optpiccount,$arPicProps[$arOptProperty['CODE']]['PROPERTY_VALUE_ID'])) 
	   			{
	   				CIBlockElement::SetPropertyValueCode($arFields['ID'], $arOptProperty['CODE'], Array ($arPicProps[$arOptProperty['CODE']]['PROPERTY_VALUE_ID'][$optpiccount] => Array("VALUE"=>array('MODULE_ID'=>'iblock','del'=>'Y'))));  	
	   			
	   			}
	   		$optpiccount++;
		} 			 
		 	
	}
  }

  function fn_deletesectionpictures(&$arFields,$arOriginalPicture,$OPT_PROPERTY,$count)
  {  
	$arPicOptUpdate=array();
	$optpiccount=$count*count($this->arPicOptValues);
	  
	if ($arFields['ID']>0)
	{
		$arSectionPics=array();
		$arFilter=array("IBLOCK_ID"=>$arFields['IBLOCK_ID'], "ID"=>$arFields['ID']);
     	$res = CIBlockSection::GetList(Array(), $arFilter, false,Array("UF_OPT_MORE_PHOTO"));
     	if ($res->SelectedRowsCount()>0){
  			$arSectionPics=$res->GetNext();   	
     	}	
		 
		for($i=0;$i<count($this->arPicOptValues);$i++)
		{
	   		if (array_key_exists($optpiccount,$arSectionPics[$this->arIBlockSectionPictOpt[$arFields['IBLOCK_ID']][1]])) {
	   			$arFields[$this->arIBlockSectionPictOpt[$arFields['IBLOCK_ID']][1]][$optpiccount]['del']=1;
	   		}
	   		$optpiccount++;
		} 			 
		 	
	}
  }

  
  function fn_make_watermark(&$arFile)
  {
  	$wm_filename='';
  	
    if (count($this->arWaterMarkSettings)<1) {return;}
   
	if ($arFile["type"]=='image/png') {$img_dest=imagecreatefrompng($arFile["tmp_name"]);}
	 elseif ($arFile["type"]=='image/gif') {$img_dest=imagecreatefromgif($arFile["tmp_name"]);}
	 else {$img_dest=imagecreatefromjpeg($arFile["tmp_name"]);}
	
	 $arOriginalImageDimensions = getimagesize($arFile["tmp_name"]);
	 
	 foreach ($this->arWaterMarkSettings as $key => $arWaterMarkValue)
	 {
	  if (
	  		(($arWaterMarkValue[0]=='WIDTH') && ($arWaterMarkValue[1]<=$arOriginalImageDimensions[0])) 
	  		|| 
	  		(($arWaterMarkValue[0]=='HEIGHT') && ($arWaterMarkValue[1]<=$arOriginalImageDimensions[1]))
	  	 )
	  {
	   $wm_filename=$arWaterMarkValue[2];
	  }  
	 }
      	
     if (strlen($wm_filename)<1) {return;}	
		
     $arWaterMarkImageDimensions = getimagesize($_SERVER["DOCUMENT_ROOT"].$wm_filename);      
     $water_img = imagecreatefrompng($_SERVER["DOCUMENT_ROOT"].$wm_filename); 	

	 $x_water=($arOriginalImageDimensions[0]/2)-($arWaterMarkImageDimensions[0]/2);
	 $y_water=($arOriginalImageDimensions[1]/2)-($arWaterMarkImageDimensions[1]/2);
	 imagecopy($img_dest, $water_img, $x_water, $y_water, 0, 0, $arWaterMarkImageDimensions[0], $arWaterMarkImageDimensions[1]);	
	
	if ($arFile["type"]=='image/png') {imagepng($img_dest,$arFile["tmp_name"]);}
	 elseif ($arFile["type"]=='image/gif') {imagegif($img_dest,$arFile["tmp_name"]);}
	 else {imagejpeg($img_dest,$arFile["tmp_name"]);}
	  
  }
}
?>