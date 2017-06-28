<?
	ini_set('display_errors','On');

	$img = ImageCreateFromJPEG("blank.jpg");		
	$color = ImageColorAllocate ($img, 90, 90, 90);  
	ImageRectangle ($img, 0, 0, 250, 100, $color);    
	$font = $_SERVER["DOCUMENT_ROOT"].'/partneram/babyliss/tech-form/arial.ttf';
	$text = 'ондрбепфдемн';
	$fullDate = getdate();

	if (strlen($fullDate['mday']) < 2)
		$fullDate['mday'] = '0'.$fullDate['mday'];
	if (strlen($fullDate['mon']) < 2)
		$fullDate['mon'] = '0'.$fullDate['mon'];
	if (strlen($fullDate['year']) < 2)
		$fullDate['year'] = '0'.$fullDate['year'];
	if (strlen($fullDate['hours']) < 2)
		$fullDate['hours'] = '0'.$fullDate['hours'];
	if (strlen($fullDate['minutes']) < 2)
		$fullDate['minutes'] = '0'.$fullDate['minutes'];

	$date = $fullDate['mday'].'.'.$fullDate['mon'].'.'.$fullDate['year'];
	$time = $fullDate['hours'].':'.$fullDate['minutes'];

	function iso2uni ($isoline) {
		for ($i=0; $i < strlen($isoline); $i++){
			$thischar=substr($isoline,$i,1);
			$charcode=ord($thischar);
			$uniline.=($charcode>175)?"&#".(1040+($charcode-176)).";":$thischar;
		}
		return $uniline;
	}

	ImageTTFText ($img, 20, 0, 10, 40, $color, $font,iso2uni( convert_cyr_string($text ,"w","i")));
	ImageTTFText ($img, 12, 0, 40, 80, $color, $font,iso2uni( convert_cyr_string($date ,"w","i")));
	ImageTTFText ($img, 12, 0, 160, 80, $color, $font,iso2uni( convert_cyr_string($time ,"w","i")));
	ImagePng ($img, "stamp.jpg"); 
?>