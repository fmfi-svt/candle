<div class="timetableExportOptionsSmall">
<?php
$rawUrl = $sf_data->getRaw('url');

echo link_to('ICS', Candle::addFormat($rawUrl, 'ics'), array('rel' => 'nofollow'));
echo " ";
echo link_to('CSV', Candle::addFormat($rawUrl, 'csv'), array('rel' => 'nofollow'));
echo " ";
echo link_to('TXT', Candle::addFormat($rawUrl, 'txt'), array('rel' => 'nofollow'));
echo " ";
echo link_to('Predmety', Candle::addFormat($rawUrl, 'list'), array('rel' => 'nofollow'));
?>
</div>