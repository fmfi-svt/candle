<div class="timetableFooter">
<?php 
    $rawUrl = $sf_data->getRaw('url');
    include_partial('timetable/exportOptionsSmall',
        array('url'=>$rawUrl));
?>
    <div class="timetableVysvetlivkyLink">
        <a href="http://www.fmph.uniba.sk/index.php?id=787">vysvetlivky k rozvrhu</a>
    </div>
</div>