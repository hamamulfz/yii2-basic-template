<?php

/**
 * User: Aziz (mufti.aziz@gmail.com)
 * Date: 2025/04/02
 * Time: 16.30
 */

namespace app\helpers;

use yii;
use yii\base\DynamicModel;
use DateInterval;
use DateTime;

class Utils
{
    public static function randNomor()
    {
        $huruf = "ABCDEFGHJKLMNPQRTUVWXYZ";
        $digits = 10;
        return $huruf[\rand(0, 22)] . $huruf[\rand(0, 22)] . $huruf[\rand(0, 22)] . "_" . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    public static function randOtp($digits = 6)
    {
        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    public static function generateTicketNo($prefix)
    {
        $alphabets = '';
        for ($i = 0; $i < 3; $i++) {
            $alphabets .= chr(rand(65, 90)); // Generate random uppercase letters (A-Z)
        }
        $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Generate 3-digit random number (with leading zeros)
        return $prefix . $alphabets . $numbers;
    }

    public static function sendEmail($emailTo, $subject, $message, $options = array())
    {
        $emailSend = Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['notifEmail'] => Yii::$app->name . ' No-Reply'])
            ->setTo($emailTo)
            ->setSubject($subject)
            ->setHtmlBody($message);
        return $emailSend->send();
    }

    public static function sendTemplateEmail($model, $emailTo, $name, $view, $subject, $params = [], $attach = false)
    {
        \Yii::$app->mailer->view->params['model'] = $model;
        \Yii::$app->mailer->view->params['name'] = $name;
        \Yii::$app->mailer->view->params['site'] = [
            "website" => @$model->url ?? Yii::$app->params['hostIntUrl'],
            "appName" => Yii::$app->name
        ];
        $emailAddress = Yii::$app->params['sendNotifEmail'] ? $emailTo : 'mufti.aziz@gmail.com';
        $result = \Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params)
            ->setFrom([
                Yii::$app->params['notifEmail'] => Yii::$app->name . ' No-Reply'
            ])
            ->setTo([$emailAddress => $name])
            ->setBcc(Yii::$app->params['emailBcc'])
            ->setSubject($subject)
            ->send();
        \Yii::$app->mailer->view->params['userName'] = null;
        return $result;
    }

    public static function sendTemplateBulkEmail($model, $emailTo, $view, $subject, $params = [])
    {
        \Yii::$app->mailer->view->params['model'] = $model;
        // \Yii::$app->mailer->view->params['tanggal_jadwal'] = $model->tanggal_jadwal;
        $result = \Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params)
            ->setFrom([Yii::$app->params['notifEmail'] => Yii::$app->name . ' No-Reply'])
            ->setTo($emailTo)
            ->setSubject($subject)
            ->send();
        return $result;
    }

    public static function Vcalendar(
        $nameOrganizer,
        $emailOrganizer,
        $description,
        $attendeeName,
        $attendeeEmail,
        $startDateTime,
        $endDateTime,
        $timeStamp,
        $summary,
        $timeStampLast,
        $location
    ) {
        define('ICAL_FORMAT', 'Ymd\THis\Z');
        // Begin calendar
        $icalObject = "BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
PRODID:Train Driver Department MRT-Jakarta
BEGIN:VTIMEZONE
TZID:SE Asia Standard Time
BEGIN:STANDARD
DTSTART:16010101T000000
TZOFFSETFROM:+0700
TZOFFSETTO:+0700
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:16010101T000000
TZOFFSETFROM:+0700
TZOFFSETTO:+0700
END:DAYLIGHT
END:VTIMEZONE\n";
        $icalObject .=
            "BEGIN:VEVENT
ORGANIZER;CN=" . $nameOrganizer . ":" . $emailOrganizer . "
ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=" . $attendeeName . ":mailto:" . $attendeeEmail . "
DESCRIPTION;LANGUAGE=en-US:Catatan Dari Duty Manager: " . $description . "
DTSTART;TZID=SE Asia Standard Time:" . date(ICAL_FORMAT, strtotime($startDateTime)) . "
DTEND;TZID=SE Asia Standard Time:" . date(ICAL_FORMAT, strtotime($endDateTime)) . "
DTSTAMP" . date(ICAL_FORMAT, strtotime($timeStamp)) . "
UID:" . rand(0, 22) . "
SUMMARY;LANGUAGE=en-US:" . $summary . "
STATUS:" . strtoupper('CONFIRMED') . "
LAST-MODIFIED:" . date(ICAL_FORMAT, strtotime($timeStampLast)) . "
LOCATION;LANGUAGE=en-US:" . $location . "
END:VEVENT\n";
        // close calendar
        $icalObject .= "END:VCALENDAR";
        return $icalObject;
    }

    public static function sendTemplateEmailWithcalendar($model, $emailTo, $name, $view, $subject, $params, $dmName, $dmEmail, $startDateTime, $endDateTime, $location, $summary, $dm_note)
    {
        date_default_timezone_set("Asia/Bangkok");
        $today = date('Y-m-d');
        \Yii::$app->mailer->view->params['name'] = $name;
        \Yii::$app->mailer->view->params['model'] = $model;
        // \Yii::$app->mailer->view->params['tanggal_jadwal'] = $model->tanggal_jadwal;
        $attach = self::Vcalendar(
            $dmName, //nameOrganizer,
            $dmEmail, //emailOrganizer,
            $dm_note, //description,
            $name, //attendeeName,
            $emailTo, //attendeeEmail,
            $startDateTime,
            $endDateTime,
            $today, //timeStamp,
            $summary, //summary,
            $today, //timeStampLast
            $location
        );
        $result = \Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params)
            ->setFrom([Yii::$app->params['notifEmail'] => Yii::$app->name . ' No-Reply'])
            ->setTo([$emailTo => $name])
            ->attachContent($attach, ['fileName' => 'invite.ics', 'contentType' => 'text/plain'])
            ->setSubject($subject)
            ->send();
        \Yii::$app->mailer->view->params['userName'] = null;
        return $result;
    }

    public static function sendNotifScheduleChanged($model, $subject, $viewManager, $viewStaff)
    {
        //notif to manager_name
        if (@$model->managerEmail) {
            @Utils::sendTemplateEmail($model, $model->managerEmail, $model->managerName, $viewManager, $subject);
        }
        // @Utils::sendTemplateEmail($model, "mufti.aziz@gmail.com", "Mufti Aziz Ahmad", $viewManager, $subject);
        //notif ke occ_user
        if (@$model->userEmail) {
            @Utils::sendTemplateEmail($model, $model->userEmail, $model->userName, $viewStaff, $subject);
        }
        return true;
    }


    public static function sendNotif($model, $subject)
    {
        @Utils::sendTemplateEmail($model, "mufti.aziz@gmail.com", "Mufti Aziz Ahmad", 'notif-error', $subject);
        return true;
    }

    public static function firstDateMonth($year, $month)
    {
        if (\DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-01') !== FALSE) {
            $day = date_create(date($year . '-' . $month . '-01'));
            return date_format($day, "Y-m-d");
        } else {
            return null;
        }
    }

    public static function lastDateMonth($year, $month)
    {
        if ($month == 12) {
            $year++;
            $month = 1;
        } else {
            $month++;
        }
        if (\DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-01') !== FALSE) {
            $day = date_create(date($year . '-' . $month . '-01'));
            date_sub($day, date_interval_create_from_date_string("1 days"));
            return date_format($day, "Y-m-d");
        } else {
            return null;
        }
    }

    public static function dateToIndonesia($date, $tipe = 'Long', $delimiter = " ")
    {
        if ($tipe == 'Long') {
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
        } else if ($tipe == 'Short') {
            $months = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Ags',
                9 => 'Sep',
                10 => 'Okt',
                11 => 'Nov',
                12 => 'Des',
            ];
        }
        $newDate = date_create($date);
        return date_format($newDate, "j") . $delimiter . $months[date_format($newDate, "n")] . $delimiter . date_format($newDate, "Y");
    }

    public static function dateTimeToIndonesia($date, $tipe = 'Long', $delimiter = " ")
    {
        if ($tipe == 'Long') {
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
        } else if ($tipe == 'Short') {
            $months = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Ags',
                9 => 'Sep',
                10 => 'Okt',
                11 => 'Nov',
                12 => 'Des',
            ];
        }
        $newDate = date_create($date);
        return date_format($newDate, "j") . $delimiter . $months[date_format($newDate, "n")] . $delimiter . date_format($newDate, "Y") . ' ' . date_format($newDate, "H:i:s");
    }

    public static function hariPekan($date)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $newDate = date_create($date);
        return $hari[date_format($newDate, "w")];
    }

    public static function namaBulan($month)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        return $months[$month];
    }

    public static function convertExcelDate($date)
    {
        if (is_int(($date))) {
            $dateConvert = ($date - 25569) * 86400;
        } else {
            return null;
        }
        return gmdate("Y-m-d", $dateConvert);
    }

    public static function SecToTime($sec)
    {
        $hour = floor($sec / (60 * 60));
        $sec -= ($hour * (60 * 60));
        $minutes = floor($sec / 60);
        $sec -= ($minutes * 60);
        return sprintf("%02d", $hour) . ":" . sprintf("%02d", $minutes) . ":" . sprintf("%02d", $sec);
    }

    public static function convertTimeOver24($time)
    {
        if (substr($time ?? '', 0, 2) >= 24) {
            $timeNew = (substr($time, 0, 2) - 24) . substr($time, 2, 6);
        } else {
            $timeNew = $time;
        }
        return $timeNew;
    }

    public static function TimeToSec($time)
    {
        $newTime = Utils::convertTimeOver24($time);
        $parsed = date_parse($newTime);
        $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
        if ($time !== $newTime) {
            $seconds += 3600 * 24;
        }
        return $seconds;
    }


    public static function sendNodeNotif($channel, $type, $title, $content)
    {
        $url = "https://banvak-dev.freeddns.org:3000/message";
        $data = ["message" => [
            "api_key" => Yii::$app->params["pusherKey"],
            "type" => $type,
            "channel" => $channel,
            "title" => $title,
            "body" => $content
        ]];
        $content = json_encode($data);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array("Content-type: application/json")
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $status;
    }

    public static function getClientIp()
    {
        $ip = false;

        $seq = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($seq as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function createUrlSlug($urlString)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($urlString));
        return $slug;
    }

    public static function FloatToTime($floatNumber)
    {
        if (($floatNumber)) {
            $jamUnfloor = $floatNumber * 24.00001;
            $jam = floor($jamUnfloor);
            $menitUnfloor = ($jamUnfloor - $jam) * 60;
            $menit = floor($menitUnfloor);
            $detik = ($menitUnfloor - $menit) * 60;
            return str_pad($jam, 2, '0', STR_PAD_LEFT) . ':' . str_pad($menit, 2, '0', STR_PAD_LEFT) . ':' . str_pad(round($detik), 2, '0', STR_PAD_LEFT);
        } else {
            return '00:00:00';
        }
    }

    public static function convertStatus($json)
    {
        $res = json_decode($json);
        if (!$res) {
            return null;
        }
        $isArray = is_array($res);

        if ($isArray) {
            $var = $res[0];
            foreach ($var as $key => $value) {
                if ($value) {
                    return $key;
                    break;
                }
            }
        } else {
            $var = $res;
            foreach ($var as $key => $value) {
                if ($value) {
                    return $key;
                    break;
                }
            }
        }
    }


    public static function padLeft($val)
    {

        if (strlen($val) == 1) {
            return "000" . $val;
        }

        if (strlen($val) == 2) {
            return "00" . $val;
        }

        if (strlen($val) == 3) {
            return "0" . $val;
        }

        return $val;
    }

    public static function TimeDiffSec($firstTime, $lastTime)
    {
        $result = strtotime(Utils::convertTimeOver24($lastTime ?? '')) - strtotime(Utils::convertTimeOver24($firstTime ?? ''));
        if ($result < 0) {
            $result += 24 * 60 * 60;
        }
        return $result;
    }

    public static function findColumnNo($src)
    {
        $urut = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $j = 0;
        $k = 0;
        $col1 = '';
        $col2 = '';
        for ($i = 0; $i <= 26 * 2; $i++) {
            if ($j > 0) {
                $col1 = $urut[$j - 1];
            }
            $col2 = $urut[$k];
            if (($i > 0) && (($i + 1) % 26 == 0)) {
                $j++;
                $k = 0;
            } else {
                $k++;
            }
            if ($col1 . $col2 == $src) {
                return $i;
            }
        }
        return 0;
    }

    public static function findColumnName($no)
    {
        $urut = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        $sisa = $no;
        while ($sisa > 0) {
            if ($sisa > 26) {
                $hasil = floor(($sisa - 1) / 26);
            } else {
                $hasil = $sisa;
            }
            $result = $result . $urut[(int) ($hasil - 1)];
            $sisa -= 26 * $hasil;
        }
        return $result;
    }

    public static function leadingZero($value, $number)
    {
        return str_pad($value, $number, '0', STR_PAD_LEFT);
    }

    public static function firstDate($year, $month)
    {
        return date($year . '-' . Utils::leadingZero($month, 2) . '-01');
    }

    public static function lastDate($year, $month)
    {
        return date("Y-m-t", strtotime(Utils::firstDate($year, $month)));
    }

    public static function isEven($number)
    {
        return ($number % 2 == 0);
    }

    public static function TimeAdd($timeSt, $timeTo)
    {
        if ($timeSt) {
            if ($timeSt >= "24:00:00") {
                $timeSt = Utils::convertTimeOver24($timeSt);
            }
            $timeDT = new DateTime($timeSt);
            return $timeDT->getTimestamp() + $timeTo;
        }
        return 0;
    }

    public static function TimeAddHis($timeSt, $timeTo)
    {
        $result = Utils::TimeAdd($timeSt, $timeTo);
        if (($timeSt >= "23:00:00") && (date("H", $result) <= "03")) {
            return (date("H", $result) + 24) . date(":i:s", $result);
        } else {
            return date("H:i:s", $result);
        }
    }

    public static function generateInitial(string $name = null): string
    {
        if (!$name) {
            return "NN";
        }
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8') .
                    mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8'
            );
        }
        return Utils::makeInitialsFromSingleWord($name);
    }

    protected static function makeInitialsFromSingleWord(string $name): string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
        }
        return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
    }

    public static function titleRole($title)
    {
        $result = str_replace("_", " ", $title);
        return ucwords($result);
    }

    public static function convertArray($arr)
    {
        $result = [];
        foreach ($arr as $item) {
            $result[] = $item;
        }
        return $result;
    }

    public static function addDate($date, $dateNum, $dateFormat = 'Y-m-d')
    {
        $datetime = new DateTime($date);
        if ($dateNum > 0) {
            $datetime->modify('+' . $dateNum . ' day');
        } else {
            $datetime->modify($dateNum . ' day');
        }
        return $datetime->format($dateFormat);
    }

    public static function addTime($date, $timeNum, $modify = 'hour', $dateFormat = 'Y-m-d')
    {
        $datetime = new DateTime($date);
        $datetime->modify('+' . $timeNum . ' ' . $modify);
        return $datetime->format($dateFormat);
    }

    public static function parseDate($date, $parse)
    {
        $d = date_parse_from_format("Y-m-d", $date);
        return $d[$parse];
    }

    public static function imgCheck($type, $value)
    {
        if (!isset($value)) {
            return "";
        }
        if ($type) {
            if ($value == 1) {
                return "<img src='" . Yii::getAlias('@baseweb/imgs/checking.png') . "' alt='checked'>";
            } else {
                return "<img src='" . Yii::getAlias('@baseweb/imgs/cross.png') . "' alt='checked'>";
            }
        } else {
            return $value;
        }
    }


    static function intraDayConverter($originalDate, $originalTime)
    {
        if ($originalDate == null || $originalTime == null) {
            return null;
        }
        //         $originalDate = "2023-08-28";
        // $originalTime = "25:05:30";

        // Split the time string into hours, minutes, and seconds
        $timeParts = explode(':', $originalTime);
        $hours = intval($timeParts[0]);
        $minutes = intval($timeParts[1]);
        $seconds = intval($timeParts[2]);

        // Calculate the extra days and adjust hours
        $extraDays = floor($hours / 24);
        $adjustedHours = $hours % 24;

        // Create a new DateTime object with the original date
        $dateTime = new DateTime($originalDate);
        $dateTime->add(new DateInterval("P{$extraDays}D"));
        $dateTime->setTime($adjustedHours, $minutes, $seconds);

        // Convert to desired format
        $newDateTimeString = $dateTime->format('Y-m-d H:i:s');

        return $newDateTimeString;
    }

    public static function PartOfDate($dateString, $element, $type = 'int')
    {
        $dateTime = strtotime($dateString);
        if ($type == 'int') {
            return (int) date($element, $dateTime);
        } else {
            return date($element, $dateTime);
        }
    }




    public static function roundTime(\DateTime $datetime, $precision = 10)
    {

        $ts = $datetime->getTimestamp();
        $s = ($precision);
        $remainder = $ts % $s;

        if ($remainder > 0) {
            $datetime->setTimestamp($ts + $s - $remainder);
        }

        return $datetime;
    }

    public static function fileSize($fileSize)
    {
        $size = $fileSize;
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
