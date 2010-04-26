<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
include_partial('timetable/editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

?>
<h2>Exportovať rozvh</h2>
<p>Môžete si vybrať niektorý z nasledujúcich výstupných formátov:</p>
<ul>
    <li><?php echo link_to('iCalendar (.ics)', '@timetable_show?sf_format=ics&id='.$timetable_id); ?>
        - kalendárový formát podporovaný vačšinou kalendárových klientov ako
        Google Calendar, MS Outlook, Mozilla Sunbird, Mozilla Thunderbird+Lightning, Evolution, KOrganizer a ďaľšími</li>
    <li><?php echo link_to('Comma Separated Values (.csv)', '@timetable_show?sf_format=csv&id='.$timetable_id); ?>
        - jednoduchý tabuľkový formát podporovaný vačšinou tabuľkových aplikácii ako
        OpenOffice.org Calc, MS Excel, Google Docs, KOffice KSpread a ďaľšie</li>
    <li><?php echo link_to('Text (.txt)', '@timetable_show?sf_format=txt&id='.$timetable_id); ?>
        - jednoduchý textový zoznam hodín, určený pre čítanie človekom</li>
    <li><?php echo link_to('eXtensible Markup Language (.xml)', '@timetable_show?sf_format=xml&id='.$timetable_id); ?>
        - XML reprezentácia hodín v rozvrhu, pravdepodobne zaujímavé len pre programátorov</li>
</ul>