<?php
setcookie('user',null,time() - 3600, '/');
header('Location: login.php');





