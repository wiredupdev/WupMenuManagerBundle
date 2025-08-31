<?php

require_once __DIR__.'/../vendor/autoload.php';

Symfony\Component\ErrorHandler\ErrorHandler::register(null, false);

(new Symfony\Component\Filesystem\Filesystem())->remove(__DIR__.'/../var');
