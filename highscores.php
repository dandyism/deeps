<table class="table">
<thead>
<tr><td>User</td><td>Score</td><td>Date</td></tr>
</thead>
<tbody class="table-striped">
<?php
$records = fRecordSet::build('Highscore', array(), array('score' => 'desc'));

$records->precreateUsers();
foreach ($records as $record) {
    echo '<tr><td>' . $record->createUser()->prepareUsername() . '</td><td>' . $record->getScore() . '</td><td>' . $record->getRetreatDate() . '</td></tr>';
}
?>
<tbody>
</table>
