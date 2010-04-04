<form action="" method="post">

<ul class="vysledky_hladania">

<?php foreach ($subjects as $subject): ?>

<li class="predmet">
    <div class="predmet_header">
        <div class="predmet_nazov"><?php echo $subject->getName() ?></div>
        <div class="predmet_kod"><?php echo $subject->getShortCode() ?></div>
        <input type="hidden" name="subjectBefore[<?php echo $subject->getId()?>]" value="" />
        <input class="predmet_check" type="checkbox" name="subject[<?php echo $subject->getId()?>]"/>
    </div>

    <table>
      <thead>
        <tr>
          <th>Typ</th><th>Deň</th><th>Čas</th><th>Kde</th><th>Kto</th><th>Zobraziť</th>
        </tr>
      </thead>
        
      <tbody>
        <?php foreach ($subject->getLessons() as $lesson): ?>
        <tr>
          <td><?php echo $lesson->getType()->getCode() ?></td>
          <td><?php echo Candle::formatShortDay($lesson->getDay()) ?></td>
          <td><?php echo Candle::formatTime($lesson->getStart()) ?></td>
          <td><?php echo $lesson->getRoom() ?></td>
          <td><?php foreach ($lesson->getTeachers() as $i => $teacher): 
                        if ($i>0) echo ', ';
                        echo $teacher->getShortName();
                    endforeach; ?>
          </td>
          <td class="last">
            <input type="hidden" name="lessonBefore[<?php echo $lesson->getId()?>]" value="" />
            <input type="checkbox" name="lesson[<?php echo $lesson->getId()?>]"/></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</li>

<?php endforeach; ?>

</ul>

</form>
