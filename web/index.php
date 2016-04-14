<?php

require_once('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use DMF\Framework;

/* 初始化Container，并开启调试模式 */
$framework = new Framework(TRUE);
$container = $framework->boot();

$dispatcher = $container->get('event_dispatcher');
$resolver = $container->get('controller_resolver');

/*
 * 开始处理Http请求，调用相应的代码
 */ 
$request = Request::createFromGlobals();
$kernel = new HttpKernel($dispatcher, $resolver);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
