<?php
setcookie('user', null, time()-1, '/');
session_destroy();





