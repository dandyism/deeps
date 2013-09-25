<div class="container">
    <h1><?php echo $this->get('game_name'); ?></h1>
    <nav role="navigation" class="navbar navbar-default">
        <ul class="nav navbar-nav">
<?php
if (fAuthorization::checkAuthLevel('player')) {
?>
                <li><a href="/?action=logout">Logout</a></li>
<?php
} else {
?>
                <li><a href="/login/" >login</a></li> 
                <li><a href="/register/" >register</a></li>
<?php
}
?>
        </ul>
    </nav>

<?php
if (fMessaging::check('error', 'user')) {
    fMessaging::show('error', 'user', 'alert alert-danger');
}
else {
    fMessaging::show('success', 'user', 'alert alert-success');
}
?>
