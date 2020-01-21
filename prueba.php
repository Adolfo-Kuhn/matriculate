<?php

session_start();

$_SESSION['user'] = 'adolfo';
$user = $_SESSION['user'];

$_SESSION[$user]['set']['fondo'] = 'color';
$_SESSION[$user]['set']['texto'] = 'texto';
$_SESSION[$user]['set']['hover'] = 'hover';
$_SESSION[$user]['set']['visitas'] = 'visitas';
$_SESSION['inicio'] = 'inicio';

var_dump($_SESSION[$user]);

unset($_SESSION[$user]['set']);

var_dump($_SESSION[$user]);
