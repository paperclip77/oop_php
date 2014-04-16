<?php

function __autoload($class){
    require $class.'.php';
}

Reflection::export(new ReflectionClass('MySQLConnect'));


?>