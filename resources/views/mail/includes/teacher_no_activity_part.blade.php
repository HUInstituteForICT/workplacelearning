<?php
/** @var \App\Student $student */
?>

<div class="panel panel-default">
    <div class="panel-body">
        {{ $student->getName() }} bij {{ $student->getCurrentWorkplaceLearningPeriod()->workplace->wp_name }}
    </div>
</div>
