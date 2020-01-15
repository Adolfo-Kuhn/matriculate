<?php

session_start();

//Aquí debe ir el código necesario para eliminar las cookies
//si el usuario así lo indica en la preferencias.

session_unset();
session_destroy();

header('Location: ./index.php');
