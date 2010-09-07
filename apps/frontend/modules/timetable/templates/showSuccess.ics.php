<?php

// vyegenruje iCalendar vystup pomocou iCalcreator knizice
// vid tiez RFC 2445
// http://tools.ietf.org/html/rfc2445

require_once(sfConfig::get('sf_lib_dir').'/vendor/iCalcreator/iCalcreator.class.php');

function datetime2icsdatetime($datetime=null) {
    if ($datetime == null) {
        $datetime = time();
    }

    $timeinfo = getdate($datetime);
    return array(
        'year'=>$timeinfo['year'],
        'month'=>$timeinfo['mon'],
        'day'=>$timeinfo['mday'],
        'hour'=>$timeinfo['hours'],
        'min'=>$timeinfo['minutes'],
        'sec'=>$timeinfo['seconds']
    );
}

function lessonTime($basetime, $day, $time) {
    return datetime2icsdatetime($basetime + $day*86400 + $time*60);
}

$calendar= new vcalendar();
$calendar->setConfig('unique_id', $sf_request->getHost()); // todo pridat konfiguracnu volbu

// zakladny cas od ktoreho budu vyexportovane hodiny
$basetime = sfConfig::get('app_semester_start');

// koncovy cas opakovania (koniec semestra)
$endtime = sfConfig::get('app_semester_end')+86400;

foreach ($layout->getLessons() as $lesson) {

    $vevent = new vevent();
    $vevent->setProperty( 'dtstart', lessonTime($basetime, $lesson['day'], $lesson['start']));
    $vevent->setProperty( 'dtend', lessonTime($basetime, $lesson['day'], $lesson['end']));
    $vevent->setProperty( 'location', $lesson['Room']['name'] );
    $vevent->setProperty( 'summary', $lesson['Subject']['name'] );
    $vevent->setProperty( 'categories', $lesson['LessonType']['name']);
    $description = $lesson['LessonType']['name']."\r\n\r\n";
    $description .= 'Vyučujúci:'."\r\n";
    foreach ($lesson['Teacher'] as $teacher) {
        $description .= ' - '.Candle::formatShortName($teacher);
        $description .= "\r\n";
    }
    $vevent->setProperty( 'description', $description );
    $vevent->setProperty( 'rrule' , array(
                            'FREQ'=>'WEEKLY',
                            'UNTIL'=>datetime2icsdatetime($endtime)));
    $calendar->setComponent ( $vevent );
}

$calendar->returnCalendar();