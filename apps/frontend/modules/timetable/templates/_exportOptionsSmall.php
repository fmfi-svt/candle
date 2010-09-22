<div class="timetableExportOptionsSmall">
<?php
$rawUrl = $sf_data->getRaw('url');

echo link_to('ICS', Candle::addFormat($rawUrl, 'ics'));
echo " ";
echo link_to('CSV', Candle::addFormat($rawUrl, 'csv'));
echo " ";
echo link_to('TXT', Candle::addFormat($rawUrl, 'txt'));
echo " ";
echo link_to('Predmety', Candle::addFormat($rawUrl, 'list'));
?>
</div>