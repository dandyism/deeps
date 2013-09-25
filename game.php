<?php
$player = new Player();
$encounter = new Encounter($player->depth);
?>

<div class="row">
    <div class="col-md-4">strength: <?php echo $player->strength; ?></div>
    <div class="col-md-4">score: <?php echo $player->score . ' +' . $encounter->score; ?></div>
    <div class="col-md-4">depth: <?php echo $player->depth; ?></div>
</div>

<?php
echo '<p>' . $encounter->text . '</p>';
$player->score += $encounter->score;
$player->save();
?>
