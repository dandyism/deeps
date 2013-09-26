<table class="table">
<thead>
<tr><td>Score</td><td>Date</td></tr>
</thead>
<tbody class="table-striped">
<?php
$result = Database::retrieve('highscores', array('score > 0'));

foreach ($result as $row) {
    echo '<tr><td>' . $row['score'] . '</td><td>' . $row['retreat_date'] . '</td></tr>';
}
?>
<tbody>
</table>
