<?php

slot('title', Candle::formatLongName($teacher['name']));

slot('top');
include_component('timetable','top',
        array('timetable'=>$timetable,
              'timetable_url'=>url_for('@timetable_teacher_show?id='.$teacher_id)));
include_partial('timetableTeacher/menu', array('teacher_id'=>$teacher_id));
end_slot();

?>
<h2>Exportovať rozvh</h2>
<p>Môžete si vybrať niektorý z nasledujúcich výstupných formátov:</p>
<ul>
    <li><?php echo link_to('iCalendar (.ics)', '@timetable_teacher_show?sf_format=ics&id='.$teacher_id); ?>
        - kalendárový formát podporovaný vačšinou kalendárových klientov ako
        Google Calendar, MS Outlook, Mozilla Sunbird, Mozilla Thunderbird+Lightning, Evolution, KOrganizer a ďaľšími</li>
    <li><?php echo link_to('Comma Separated Values (.csv)', '@timetable_teacher_show?sf_format=csv&id='.$teacher_id); ?>
        - jednoduchý tabuľkový formát podporovaný vačšinou tabuľkových aplikácii ako
        OpenOffice.org Calc, MS Excel, Google Docs, KOffice KSpread a ďaľšie</li>
    <li><?php echo link_to('Text (.txt)', '@timetable_teacher_show?sf_format=txt&id='.$teacher_id); ?>
        - jednoduchý textový zoznam hodín, určený pre čítanie človekom</li>
    <li><?php echo link_to('eXtensible Markup Language (.xml)', '@timetable_teacher_show?sf_format=xml&id='.$teacher_id); ?>
        - XML reprezentácia hodín v rozvrhu, pravdepodobne zaujímavé len pre programátorov</li>
</ul>