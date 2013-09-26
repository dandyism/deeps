<?php
$player = new Player();
$encounter = new Encounter($player->depth);
?>

<div class="row">
    <div class="col-md-6">score: <?php echo $player->score . ' +' . $encounter->score; ?></div>
    <div class="col-md-6">depth: <?php echo $player->depth; ?></div>
</div>

<?php
echo '<p>' . $encounter->text . '</p>';
$player->score += $encounter->score;
$player->save();
?>
