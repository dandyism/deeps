<?php
$player = new Player();

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "delve") {
    $player->delve();
}

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
    // TODO: Submit the player's score to the highscore list.
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
$player->save();
?>
