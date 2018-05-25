<?php
 session_start();
 session_destroy();
 unset($_COOKIE['saveID']);
 unset($_COOKIE['saveUser']);
 unset($_COOKIE['savePass']);
 setcookie('savePass','',time() + 0 );
 header('Location: login');
?>