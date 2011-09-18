<?php $rawUrl = $sf_data->getRaw('url'); ?>
<p>Môžete si vybrať niektorý z nasledujúcich výstupných formátov:</p>
<ul>
    <li><?php echo link_to('iCalendar (.ics)', Candle::addFormat($rawUrl, 'ics'), array('rel' => 'nofollow')); ?>
        - kalendárový formát podporovaný vačšinou kalendárových klientov ako
        Google Calendar, MS Outlook, Mozilla Sunbird, Mozilla Thunderbird+Lightning, Evolution, KOrganizer a ďaľšími</li>
    <li><?php echo link_to('Comma Separated Values (.csv)', Candle::addFormat($rawUrl, 'csv'), array('rel' => 'nofollow')); ?>
        - jednoduchý tabuľkový formát podporovaný vačšinou tabuľkových aplikácii ako
        OpenOffice.org Calc, MS Excel, Google Docs, KOffice KSpread a ďaľšie</li>
    <li><?php echo link_to('Text (.txt)', Candle::addFormat($rawUrl, 'txt'), array('rel' => 'nofollow')); ?>
        - jednoduchý textový zoznam hodín, určený pre čítanie človekom</li>
    <li><?php echo link_to('Zoznam kódov predmetov (.list)', Candle::addFormat($rawUrl, 'list'), array('rel' => 'nofollow')); ?>
        - jednoduchý textový zoznam kódov predmetov, oddelených novým riadkom</li>
    <li><?php echo link_to('eXtensible Markup Language (.xml)', Candle::addFormat($rawUrl, 'xml'), array('rel' => 'nofollow')); ?>
        - XML reprezentácia hodín v rozvrhu, pravdepodobne zaujímavé len pre programátorov a ľudí, čo si chcú spraviť štýl na xml</li>
</ul>