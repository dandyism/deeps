<?php
$player = new User(array('email' => fAuthorization::getUserToken()));
$encounter = null;

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "retreat") {
    if ($player->getScore() > 0) {
        $highscore = new Highscore();
        $highscore->setPlayerId($player->getId());
        $highscore->setScore($player->getScore());
        $highscore->store();
        echo '<p>You take what valuables you have an return to the surface.</p>';
    }
    else {
        echo '<p>You return to the surface empty handed.</p>';
    }
    $player->setScore(0);
    $player->setDepth(0);
?>
<a href="/highscores/" class="btn btn-default btn-lg">View the High Scores</a>
<?php
}
else if ($action == "delve") {
    $depth = intval($player->getDepth());
    $depth++;
    $player->setDepth($depth);

    $encounters = fRecordSet::build(
        'Encounter',
        array('mindepth<=' => $depth, 'maxdepth>=|maxdepth=' => array($depth, null)),
        array('rand()' => 'asc')
    );

    $encounter = $encounters->getRecord(0);
    $player->setLastEncounterId($encounter->getId());
}
else if($player->getLastEncounterId() !== null) {
    $encounter = $player->createEncounter();
}

if ($encounter != null) {
    $score_inc = ($action == 'delve') ? $encounter->getScore() : 0;
?>
<div class="row">
    <div class="col-md-6">score: <?php echo $player->getScore() . ' +' . $score_inc; ?></div>
    <div class="col-md-6">depth: <?php echo $player->getDepth(); ?></div>
</div>

<?php
echo '<p>' . $encounter->getText() . '</p>';

    if ($action == "delve" && !$encounter->getDeath()) {
        $player->setScore($player->getScore() + $encounter->getScore());
    }

    if ($encounter->getDeath()) {
        $player->setScore(0);
        $player->setDepth(0);
?>
<a href="/?action=delve" class="btn btn-danger btn-lg">Start a New Game</a>
<?php
    }
    else {
?>
<a href="/?action=delve" class="btn btn-primary btn-lg">Delve</a>
<a href="/?action=retreat" class="btn btn-default btn-lg">Retreat</a>
<?php
    }
}
$player->store();
?>
