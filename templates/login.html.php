<?php 
if (isset($error)):
    echo '<div class="error">' . $error . '</div>';
endif;?>

<form action="" method="post">
    <label for="email">Your email address</label>
    <input name="email"  id="email" type="text">

    <label for="password">Your password</label>
    <input name="password"  id="password" type="password">

    <input type="submit" name="login" value="Log in">
</form>

<p> Don't have a accout?
    <a href="/author/register">Click here to register an accout</a>
</p>
