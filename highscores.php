<table class="table">
<thead>
<tr><td>User</td><td>Score</td><td>Date</td></tr>
</thead>
<tbody class="table-striped">
<?php
$limit = 10;
$page = fRequest::get('p', 'integer', 1);

$records = fRecordSet::build('Highscore', array(), array('score' => 'desc'), $limit, $page);

$pages = $records->getPages();

if ($pages > 1) {
?>
<ul class="pagination">
<?php
    echo "<li ";
    if ($page == 1) {
        echo 'class="disabled"';
    }
    echo '><a href="/highscores/' . ($page-1) . '/">&laquo;</a></li>';
    for ($i = 1; $i <= $pages; $i++) {
        echo "<li ";
        if ($i == $page) {
            echo 'class="active"><span>' . $i . '</span>';
        }
        else {
            echo '><a href="/highscores/' . $i . '/">' . $i . '</a>';
        }
        echo "</li>";
    }
    echo "<li ";
    if ($page == $pages) {
        echo 'class="disabled"';
    }
    echo '><a href="/highscores/' . ($page+1) . '/">&raquo;</a></li>';
?>
</ul>
<?php
}
$records->precreateUsers();
foreach ($records as $record) {
    echo '<tr><td>' . $record->createUser()->prepareUsername() . '</td><td>' . $record->getScore() . '</td><td>' . $record->getRetreatDate() . '</td></tr>';
}
?>
<tbody>
</table>
