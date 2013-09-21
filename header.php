<div class="container">
    <h1><?php echo $this->get('game_name'); ?></h1>
    <nav role="navigation" class="navbar navbar-default">
        <ul class="nav navbar-nav">
            <li><a href="index.php?page=login" >login</a></li> 
            <li><a href="index.php?page=registration" >register</a></li>
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
