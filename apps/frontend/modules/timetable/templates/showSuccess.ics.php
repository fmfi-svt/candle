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
$calendar->setProperty( 'METHOD', 'PUBLISH');
$calendar->setProperty( "x-wr-calname", $timetable->getName() );
$calendar->setProperty( "X-WR-CALDESC", "Rozvrh hodín vyexportovaný pomocou Candle, https://github.com/fmfi-svt/candle" );
$calendar->setProperty( "X-WR-TIMEZONE", sfConfig::get('app_calendar_timezone') );

// zakladny cas od ktoreho budu vyexportovane hodiny
$basetime = sfConfig::get('app_semester_start');

// koncovy cas opakovania (koniec semestra)
$endtime = sfConfig::get('app_semester_end')+86400;

$datetime_fields = array('TZID='.sfConfig::get('app_calendar_timezone'));

$uid_suffix = '-semester('.date('mY',$basetime).'-'.date('mY',$endtime).')-candle@'.$sf_request->getHost();

foreach ($layout->getLessons() as $lesson) {

    $vevent = new vevent();
    $vevent->setProperty( 'dtstart', lessonTime($basetime, $lesson['day'], $lesson['start']), $datetime_fields);
    $vevent->setProperty( 'dtend', lessonTime($basetime, $lesson['day'], $lesson['end']), $datetime_fields);
    $vevent->setProperty( 'location', $lesson['Room']['name'] );
    $vevent->setProperty( 'summary', $lesson['Subject']['name'] );
    $vevent->setProperty( 'categories', $lesson['LessonType']['name']);
    $vevent->setProperty( 'uid', 'lesson('.$lesson['id'].')'.$uid_suffix);
    $description = $lesson['LessonType']['name']."\r\n\r\n";
    if ($lesson['note'] !== null) {
        $description .= 'Poznámka: ' . $lesson['note'];
        $description .= "\r\n\r\n";
    }
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
