<?php
$player = new Player();
$encounter = new Encounter($player->depth);

// Game Actions
$action = fRequest::get('action', 'string');
if ($action == "delve") {
    $player->delve();
}
?>

<div class="row">
    <div class="col-md-6">score: <?php echo $player->score . ' +' . $encounter->score; ?></div>
    <div class="col-md-6">depth: <?php echo $player->depth; ?></div>
</div>

<?php
echo '<p>' . $encounter->text . '</p>';
?>
<a href="/?action=delve" class="btn btn-primary btn-lg">Delve</a>
<a href="/?action=retreat" class="btn btn-default btn-lg">Retreat</a>
<?php
$player->score += $encounter->score;
$player->save();
?>
