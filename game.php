<?php
$player = new Player();

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "retreat") {
    Database::insert('highscores', array('player_id' => $player->id, 'score' => $player->score));
    $player->score = 0;
    $player->depth = 0;
?>
<p>You take what valuables you have an return to the surface.</p>
<a href="/highscores/" class="btn btn-default btn-lg">View the High Scores</a>
<?php
}
else {
    $player->delve();

    // Must come after the player goes down
    $encounter = new Encounter($player->depth);
?>

<div class="row">
    <div class="col-md-6">score: <?php echo $player->score . ' +' . $encounter->score; ?></div>
    <div class="col-md-6">depth: <?php echo $player->depth; ?></div>
</div>

<?php
    echo '<p>' . $encounter->text . '</p>';

    if ($encounter->death) {
        $player->score = 0;
        $player->depth = 0;
?>
<a href="/?action=delve" class="btn btn-danger btn-lg">Start a New Game</a>
<?php
}
    else {
        $player->score += $encounter->score;
?>
<a href="/?action=delve" class="btn btn-primary btn-lg">Delve</a>
<a href="/?action=retreat" class="btn btn-default btn-lg">Retreat</a>
<?php
    }
}
$player->save();
?>
