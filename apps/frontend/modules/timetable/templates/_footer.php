<div class="timetableFooter">
<?php
    $rawUrl = $sf_data->getRaw('url');
    include_partial('timetable/exportOptionsSmall',
        array('url'=>$rawUrl));
?>
    <div class="timetableVysvetlivkyLink">
        <a href="http://zona.fmph.uniba.sk/studenti-a-studium/rozvrhy/casto-kladene-otazky-rozvrhy/">vysvetlivky k rozvrhu</a>
    </div>
</div>
