<!-- - There must also be some way for the user
 to log out which also involve PHP session. -->


<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
