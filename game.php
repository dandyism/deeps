<?php
$player = $this->get('user');
$encounter = null;

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "retreat") {
    if ($player->getDelveScore() > 0) {
        $new_score = intval($player->getScore());
        $new_score += intval($player->getDelveScore());
        $player->setScore($new_score);
        echo '<p>You take what valuables you have an return to the surface.</p>';
    }
    else {
        echo '<p>You return to the surface empty handed.</p>';
    }
    $player->setDelveScore(0);
    $player->setDepth(0);
?>
<a href="/highscores/" class="btn btn-default btn-lg">View the High Scores</a>
<?php
}
else if ($action == "delve" && $player->getTurns() > 0) {
    $player->setTurns(intval($player->getTurns)-1);
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
    <div class="col-md-6">Delve Score: <?php echo $player->getDelveScore() . ' +' . $score_inc; ?></div>
    <div class="col-md-6">Current Depth: <?php echo $player->getDepth(); ?></div>
</div>

<?php
echo '<p>' . $encounter->getText() . '</p>';

    if ($action == "delve" && !$encounter->getDeath() && $player->getTurns() > 0) {
        $player->setDelveScore($player->getDelveScore() + $encounter->getScore());
    }

    if ($encounter->getDeath()) {
        $player->setDelveScore(0);
        $player->setDepth(0);
?>
<a href="/?action=delve" class="btn btn-danger btn-lg">Start a New Game</a>
<?php
    }
    else {
?>
    <a href="/?action=delve" class="btn btn-primary btn-lg" <?php if($player->getTurns() <= 0) echo "disabled"; ?>>Delve</a>
<a href="/?action=retreat" class="btn btn-default btn-lg">Retreat</a>
<?php
    }
}
$player->store();
?>
