<?php

class strDate {

    public static function DateToSql($str) {
        $parts = preg_split("/[\/\.-]/", $str);
        return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }

    public static function DateTimeToSql($str) {
        return self::DateToSql($str) . ' ' . self::PartDate($str, "time");
    }

    public static function Date($str, $format = "", $pref_year = '') {
        if ($format == "y-m-d" ) {
            $outStr.=self::PartDate($str, "yyyy") . "." . self::PartDate($str, "mm") . "." . self::PartDate($str, "dd");
        }
        if ($format == "d-m-y" || $format == "d.m.y" || $format == "dd.mm.yy" ||  $format == "") {
            $outStr.=self::PartDate($str, "dd") . "." . self::PartDate($str, "mm") . "." . self::PartDate($str, "yyyy");
        }
        if ($format == "d-m") {
            $outStr.=self::PartDate($str, "dd") . " " . self::PartDate($str, "mstr");
        }
        
        
        return $outStr;
    }

    public static function DateTime($str) {
        
        return self::Date($str) . ' ' . self::PartDate($str, "time");
    }
    /* разница между датами в секундах или днях
     */

    public static function date_diff($date1, $date2='', $format = 's') {
        if ($format == 's')
            $diff = strtotime($date2) - strtotime($date1);
        if ($format == 'd')
            $diff = (strtotime($date2) - strtotime($date1)) / 86400;

        return $diff;
    }
    public static function PartDate($date_full, $part, $months = 0) {
        if (!$months) {
            $months=array();
            $months[1] = 'January';
            $months[2] = 'February';
            $months[3] = 'March';
            $months[4] = 'April';
            $months[5] = 'May';
            $months[6] = 'June';
            $months[7] = 'July';
            $months[8] = 'August';
            $months[9] = 'September';
            $months[10] = 'October';
            $months[11] = 'November';
            $months[12] = 'December';
        }// exit($date_full);
        list($date, $vremya) = explode(' ', $date_full);
       
        list( $H, $min, $sec ) = explode(':', $vremya);

        list( $year, $month, $day ) = preg_split("/[\/\.-]/", $date);

        if ($part == "dd")
            return $day;
        elseif ($part == "mm")
            return $month;
        elseif ($part == "mstr")
            return $months[$month + 0];
        elseif ($part == "msstr")
            return substr($months[$month + 0], 0, 3);
        elseif ($part == "yyyy")
            return $year;
        elseif ($part == "yy")
            return substr($year, 2);
        elseif ($part == "time")
            return $vremya;
    }

    /*
     * ДНИ НЕДЕЛИ 
     */

    public static function getWeekDays($strNumsWDays) {
        $selWeekDays = explode(',', trim($strNumsWDays, '{}'));
        if (false == $weekDays instanceof Week)
            $weekDays = new Week();
        $str = '';
        //var_dump($weekDays);
        foreach ($selWeekDays as $n) {
            $str.=$weekDays->day_names[$n] . ',';
        }
        return (count($selWeekDays) == 7) ? L_('jackpot.allweek') : trim($str, ',');
    }

}

?>
