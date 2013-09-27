<?php
$player = new User(array('email' => fAuthorization::getUserToken()));

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "retreat") {
    $highscore = new Highscore();
    $highscore->setPlayerId($player->getId());
    $highscore->setScore($player->getScore());
    $highscore->store();
    $player->setScore(0);
    $player->setDepth(0);
?>
<p>You take what valuables you have an return to the surface.</p>
<a href="/highscores/" class="btn btn-default btn-lg">View the High Scores</a>
<?php
}
else {
    $depth = intval($player->getDepth());
    $depth++;
    $player->setDepth($depth);

    $encounters = fRecordSet::build(
        'Encounter',
        array('mindepth<=' => $depth, 'maxdepth>=|maxdepth=' => array($depth, null)),
        array('rand()' => 'asc')
    );


    $encounter = $encounters->getRecord(0);
?>

<div class="row">
    <div class="col-md-6">score: <?php echo $player->getScore() . ' +' . $encounter->getScore(); ?></div>
    <div class="col-md-6">depth: <?php echo $player->getDepth(); ?></div>
</div>

<?php
    echo '<p>' . $encounter->getText() . '</p>';

    if ($encounter->getDeath()) {
        $player->setScore(0);
        $player->setDepth(0);
?>
<a href="/?action=delve" class="btn btn-danger btn-lg">Start a New Game</a>
<?php
    }
    else {
        $player->setScore($player->getScore() + $encounter->getScore());
?>
<a href="/?action=delve" class="btn btn-primary btn-lg">Delve</a>
<a href="/?action=retreat" class="btn btn-default btn-lg">Retreat</a>
<?php
    }
}
$player->store();
?>
