<?php
//Created By Morteza Rahimi v.1
class SunRize{
	var $m_dLong = +51.433;
	var $m_dLat = +35.667;
	var $m_dTZ = +3.5;
	var $m_strLoc = "ÊåÑÇä";
	var $m_bADLT = False;
	var $m_nMinRoundOff= 1 ;//5 is best related to actual accuracy of program.
	var $m_Name = array();
	var $PI ;
	var $TPI;
	var $DEGS;
	var $RADS;
	Function SunRize(){
		$this->PI = M_PI ; //3.14159265358979
		$this->TPI = 2 * $this->PI;
		$this->DEGS = 180 / $this->PI;
		$this->RADS = $this->PI / 180;
	}
	Function Test(){
		echo date("H:i",$this->GetTime(time(),1))."<BR/>";
		echo date("H:i",$this->GetTime(time(),2))."<BR/>";
		echo date("H:i",$this->GetTime(time(),3))."<BR/>";
		echo date("H:i",$this->GetTime(time(),4))."<BR/>";
		echo date("H:i",$this->GetTime(time(),5))."<BR/>";
	}
	Function DegSin($x){
		return sin($this->RADS * $x);
	}
	Function DegCos($x){
		return cos($this->RADS * $x);
	}
	Function DegTan($x){
		return tan($this->RADS * $x);
	}
	Function DegArcSin($x){
		return $this->DEGS * asin($x);
	}
	Function DegArcCos($x){
		return $this->DEGS * acos($x);
	}
	Function DegArcTan($x){
		return $this->DEGS * atan($x);
	}
	Function DegAtan2($y,$x){
		$DegAtan2 = $this->DEGS * Atan2($x, $y);
		If ($DegAtan2 < 0){
			$DegAtan2 = $DegAtan2 + 360;
		}
		return $DegAtan2 ;
	}
	Function range2pi($x){
		return $x - $this->TPI * Intval($x / $this->TPI);
	}
	Function range360($x){
		return$x - 360 * Intval($x / 360)	;
	}
	Function degdecimal($d,$m,$s = 0){
		return ($d + $m / 60 + $s / 3600);
	}
	Function myfix($num){
		if($num >= 0 ){
			if (ceil($num)==$num){
				return $num ;
			}else{
				return ceil($num)-1;
			}
		}else{
			if (ceil($num)==$num){
				return $num ;
			}else{
				return ceil($num);
			}
		}
	}
	Function day2000($year,$month,$day,
	$hour = 0,
	$sec = 0,
	$greg = 1) {
		$a = 10000.00 * $year + 100.00 * $month + $day ;
		If ($month <= 2){
			$month = $month + 12;
			$year = $year - 1;
		}
		If ($greg == 0){
			$b = -2 + $this->myfix(($year + 4716) / 4) - 1179;
		}else{
			$b = $this->myfix($year / 400) - $this->myfix($year / 100) + $this->myfix($year / 4);
		}
		$a = 365.0 * $year - 730548.5;
		return $a + $b + $this->myfix(30.6001 * ($month + 1)) + $day + ($hour + $min / 60 + $sec / 3600) / 24;
	}
	Function SunPosition($EnDate,$altitude= -0.833,$index = 1){
		$day=$this->day2000(date("Y",$EnDate), date("m",$EnDate), date("j",$EnDate));
		$utold = 180;
		$utnew = 0;
		$sinalt = Sin($altitude * $this->RADS);
		$sinphi = $this->DegSin($this->m_dLat);
		$cosphi = $this->DegCos($this->m_dLat);
		If ($index == 1){
			$signt = 1 ;
		}else{
			$signt = -1;
		}
		While (abs($utold - $utnew) > 0.01)
		{
			$utold = $utnew ;
			$days = $day + $utold / 360 ;
			$t = $days / 36525;
			$L = $this->range360(280.46 + 36000.77 * $t);
			$G = 357.528 + 35999.05 * $t;
			$lambda = $L + 1.915 * $this->DegSin($G) + 0.02 * $this->DegSin(2 * $G);
			$E = -1.915 * $this->DegSin($G) - 0.02 * $this->DegSin(2 * $G) + 2.466 * $this->DegSin(2 * $lambda) - 0.053 * $this->DegSin(4 * $lambda);
			$obl = 23.4393 - 0.13 * $t;
			$gha = $utold - 180 + $E;
			$delta = $this->DegArcsin($this->DegSin($obl) * $this->DegSin($lambda));
			$c = ($sinalt - $sinphi * $this->DegSin($delta)) / ($cosphi * $this->DegCos($delta));
			$act = $this->DegArccos($c);
			If ($c > 1)$act = 0 ;
			If ($c < -1)$act = 180 ;
			$utnew = $utold - ($gha + $this->m_dLong + $signt * $act);
		}
		return $utnew;
	}
	Function UTimeToTime($dateDay,$ut ,$RoundOff){
		$m= ($ut / 360.00) * 24 * 60 + ($this->m_dTZ + $this->m_bADLT) * 60;
		$m=$m/$RoundOff;
		$m=round($m);
		$m=$m*$RoundOff;
		$h=floor($m/60);
		if($h>24) $h=$h-24;
		$m=$m-$h*60;
		return mktime($h,$m,0,date("m",$dateDay),date("j",$dateDay),date("Y",$dateDay));
	}
	Function GetTime($dateDay,$nMode,$nRoundOff=1){
		switch ($nMode)
		{
		case 1: //Fajr
			$ut = $this->SunPosition($dateDay,-18.3,1);
			break;
		case 2: //Toloo
			$ut = $this->SunPosition($dateDay,-0.833,1);
			break;
		case 3: //Zohr
			$ut = $this->SunPosition($dateDay,90,1);
			break;
		case 4: //qroob
			$ut = $this->SunPosition($dateDay,-0.833,2);
			break;
		case 5: //maqreb
			$ut = $this->SunPosition($dateDay,-4.75,2);
			break;
		}
		return $this->UTimeToTime($dateDay, $ut, $nRoundOff);
	}
}