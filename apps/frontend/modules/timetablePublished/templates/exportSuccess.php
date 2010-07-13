<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top');
include_partial('timetablePublished/menu', array('timetable_slug'=>$timetable_slug));
end_slot();

?>
<h2>Exportovať rozvh</h2>
<p>Môžete si vybrať niektorý z nasledujúcich výstupných formátov:</p>
<ul>
    <li><?php echo link_to('iCalendar (.ics)', '@timetable_show_published?sf_format=ics&slug='.$timetable_slug); ?>
        - kalendárový formát podporovaný vačšinou kalendárových klientov ako
        Google Calendar, MS Outlook, Mozilla Sunbird, Mozilla Thunderbird+Lightning, Evolution, KOrganizer a ďaľšími</li>
    <li><?php echo link_to('Comma Separated Values (.csv)', '@timetable_show_published?sf_format=csv&slug='.$timetable_slug); ?>
        - jednoduchý tabuľkový formát podporovaný vačšinou tabuľkových aplikácii ako
        OpenOffice.org Calc, MS Excel, Google Docs, KOffice KSpread a ďaľšie</li>
    <li><?php echo link_to('Text (.txt)', '@timetable_show_published?sf_format=txt&slug='.$timetable_slug); ?>
        - jednoduchý textový zoznam hodín, určený pre čítanie človekom</li>
    <li><?php echo link_to('eXtensible Markup Language (.xml)', '@timetable_show_published?sf_format=xml&slug='.$timetable_slug); ?>
        - XML reprezentácia hodín v rozvrhu, pravdepodobne zaujímavé len pre programátorov</li>
</ul>