<?php $player = $this->get('user'); ?>
<div class="container">
    <h1><?php echo $this->get('game_name'); ?></h1>
    <nav role="navigation" class="navbar navbar-default">
        <ul class="nav navbar-nav">
<?php
if (fAuthorization::checkAuthLevel('player')) {
?>
            <li><a href="/?action=logout">Logout</a></li>
        </ul>
<?php
        echo '<div class="pull-right navbar-text">' . $player->prepareUsername() . ' <span class="well well-sm">' . $player->getScore() . '</span></div>';
} else {
?>
            <li><a href="/login/" >login</a></li> 
            <li><a href="/register/" >register</a></li>
        </ul>
<?php
}
?>
    </nav>

<?php
if (fMessaging::check('error', 'user')) {
    $err_mess = fMessaging::retrieve('error', 'user', 'alert alert-danger');
?>
    <div class="alert alert-danger alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $err_mess; ?>
    </div>
<?php
}

if (fMessaging::check('success', 'user')) {
    $succ_mess = fMessaging::retrieve('success', 'user', 'alert alert-success');
?>
    <div class="alert alert-success alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $succ_mess; ?>
    </div>
<?php
}
