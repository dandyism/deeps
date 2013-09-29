<table class="table">
<thead>
<tr><td>Rank</td><td>User</td><td>Score</td></tr>
</thead>
<tbody class="table-striped">
<?php
$limit = 10;
$page = fRequest::get('p', 'integer', 1);

$records = fRecordSet::build('User', array(), array('score' => 'desc'), $limit, $page);

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

$rank = (($page-1) * $limit) + 1;
foreach ($records as $record) {
    echo '<tr><td>' . $rank . '</td><td>' . $record->prepareUsername() . '</td><td>' . $record->getScore() . '</td></tr>';
}
?>
<tbody>
</table>
