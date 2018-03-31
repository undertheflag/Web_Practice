<?php

try{
    include __DIR__ . '/../includes/autoload.php';

    $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

    $entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\IjdbRoutes());
    $entryPoint->run();
} catch (PDOException $e){
    $title = "An error has occurred.";

	$output = 'Database error: ' . $e->getMessage().
		' in ' . $e->getFile() . ':' . $e->getLine(); //错误信息 在 文件名：行数

    include  __DIR__ . '/../templates/layout.html.php';
}
