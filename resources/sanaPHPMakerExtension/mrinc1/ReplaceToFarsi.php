<?php
// ord: Return ASCII value of character
// ord() with unicode support
function uniord($c) {
    $h = ord($c{0});
    if ($h <= 0x7F) {
        return $h;
    } else if ($h < 0xC2) {
        return false;
    } else if ($h <= 0xDF) {
        return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
    } else if ($h <= 0xEF) {
        return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
                                 | (ord($c{2}) & 0x3F);
    } else if ($h <= 0xF4) {
        return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
                                 | (ord($c{2}) & 0x3F) << 6
                                 | (ord($c{3}) & 0x3F);
    } else {
        return false;
    }
}

// this function replace special farsi characters
function nh_faReplace($str)
{
	$len = strlen($str);
	$index = 0;
	while ($index < $len)
	{
		$unicode = uniord($str[$index].$str[$index+1].$str[$index+2]);
	//	echo "<".$unicode.">";
		switch ($unicode)
		{
			
			case 1705 ://"ک"
				$str = str_replace($str[$index].$str[$index+1],"ك",$str);
				//Conke++;
				break;
			case 1740 ://"ي"
				$str[$index] = "ی";//"ي"
					$str = str_replace($str[$index].$str[$index+1],"ي",$str);
				//conye++;
			//	echo "---------".$str[$index]."-------";
				break;
			case 1609 ://"ى"
				$str = str_replace($str[$index].$str[$index+1],"ي",$str);
				//conye++;
				break;
			case 1746 ://"ے"
				$str = str_replace($str[$index].$str[$index+1],"ي",$str);
				//conye++;
				break;
			case 8204 ://"‌"(Zero Width Non-Joiner)
				$str = str_replace($str[$index].$str[$index+1]," ",$str);
				//consp++;
				break;
			case 160 ://" "(No-Break Space)
				$str[$index] =" ";
				//consp++;
				break;
			case 1726 ://"ھ"
				$str = str_replace($str[$index].$str[$index+1],"ه",$str);
				//conhe++;
				break;
			case 1729 ://"ہ"
				$str = str_replace($str[$index].$str[$index+1],"ه",$str);
				//conhe++;
				break;
			case 1749 ://"ە"
				$str = str_replace($str[$index].$str[$index+1],"ه",$str);
				//conhe++;
				break;
			case 1730 ://"ۓ"
				$str = str_replace($str[$index].$str[$index+1],"ئ",$str);
				//cone++;
				break;
		} // end of switch
		$index++;
	} // end of while
	$str= str_replace("ک","ك",$str);
	$str= str_replace("ی","ي",$str);
	return $str;
}?>