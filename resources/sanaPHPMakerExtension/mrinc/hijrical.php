<?php
global $ISLAMIC_WEEKDAYS, $ISLAMIC_MONTHS, $PERSIAN_WEEKDAYS, $PERSIAN_MONTHS;

$ISLAMIC_WEEKDAYS = array("الاحد", "الاثنين","الثلثاء", "الاربعاء", "الخميس", "الجمعه", "السبت");
$ISLAMIC_MONTHS = array("محرم","صفر","ربيع الاول","ربيع الثاني","جمادي الاول","جمادي الثاني","رجب","شعبان","رمضان","شوال","ذي القعده","ذي الحجه");
$PERSIAN_WEEKDAYS = array("يكشنبه", "دوشنبه", "سه‌شنبه", "چهارشنبه", "پنج‌شنبه", "جمعه", "شنبه");
$PERSIAN_WEEKDAYS1 = array("2", "3", "4", "5", "6", "7", "1");
$PERSIAN_MONTHS = array("فروردين","ارديبهشت","خرداد","تير","مرداد","شهريور","مهر","آبان","آذر","دي","بهمن","اسفند");

//  LEAP_ISLAMIC  --  Is a given year a leap year in the Islamic calendar ?
function leap_islamic($year)
{
    return ((($year * 11) + 14) % 30) < 11;
}

//  ISLAMICTOJD  --  Determine Julian day from Islamic date
function islamictojd($month, $day, $year)
{
	$ISLAMIC_EPOCH = 1948440;
	return ($day +
            ceil(29.5 * ($month - 1)) +
            ($year - 1) * 354 +
            floor((3 + (11 * $year)) / 30) +
            $ISLAMIC_EPOCH) - 1;
}

//  JDTOISLAMIC  --  Calculate Islamic date from Julian day
function jdtoislamic($jd)
{
	$ISLAMIC_EPOCH = 1948440;
    $year = floor(((30 * ($jd - $ISLAMIC_EPOCH)) + 10646) / 10631);
    $month = min(12,
                ceil(($jd - (29 + islamictojd(1, 1, $year))) / 29.5) + 1);
    $day = ($jd - islamictojd($month, 1, $year)) + 1;
//    return $month."/".$day."/".$year;
    return $year."/".$month."/".$day;

}

//  LEAP_PERSIAN  --  Is a given year a leap year in the Persian calendar ?
function leap_persian($year)
{
    return (((((($year - (($year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
}

//  PERSIANTOJD  --  Determine Julian day from Persian date
function persiantojd($month, $day, $year)
{
	$PERSIAN_EPOCH = 1948321;
    $epbase = $year - (($year >= 0) ? 474 : 473);
    $epyear = 474 + $epbase % 2820;

    return $day +
            (($month <= 7) ?
                (($month - 1) * 31) :
                ((($month - 1) * 30) + 6)
            ) +
            floor((($epyear * 682) - 110) / 2816) +
            ($epyear - 1) * 365 +
            floor($epbase / 2820) * 1029983 +
            ($PERSIAN_EPOCH - 1);
}

//  JDTOPERSIAN  --  Calculate Persian date from Julian day
function jdtopersian($jd)
{
    $depoch = $jd - persiantojd(1, 1, 475);
    $cycle = floor(depoch / 1029983);
    $cyear = $depoch % 1029983;
    if ($cyear == 1029982) {
        $ycycle = 2820;
    } else {
        $aux1 = floor($cyear / 366);
        $aux2 = $cyear % 366;
        $ycycle = floor(((2134 * $aux1) + (2816 * $aux2) + 2815) / 1028522) +
                    $aux1 + 1;
    }
    $year = $ycycle + (2820 * $cycle) + 474;
    if ($year <= 0) {
        $year--;
    }
    $yday = ($jd - persiantojd(1, 1, $year)) + 1;
    $month = ($yday <= 186) ? ceil($yday / 31) : ceil(($yday - 6) / 30);
    $day = ($jd - persiantojd($month, 1, $year)) + 1;
    return $year."/".$month."/".$day;
//    return $day."/".$month."/".$year;
}
?>
