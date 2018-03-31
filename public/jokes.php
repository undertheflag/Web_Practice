<?php
try {
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../classes/DatabaseTable.php';

	$jokesTable = new DatabaseTable($pdo, 'joke', 'id');
	$authorsTable = new DatabaseTable($pdo, 'author', 'id');

	$jokes = $jokesTable->findAll();

/*	$jokes = [];
	foreach ($result as $joke) {
		$author = $authorsTable->findById($joke->authorId);
		$jokes[] = [
			'id' => $joke->id,
			'joketext' => $joke['joketext'],
			'jokedate' => $joke['jokedate'],
			'name' => $author['name'],
			'email' => $author['email']
		];
	}*/

	$title = 'Jokes list';
	$totalJokes = $jokesTable->total();

	ob_start(); //output buffering
	include __DIR__ . '/../templates/jokes.html.php';
	$output = ob_get_clean(); //get the content and clean buffer
}
catch (PDOException $e) {
	$title = "An error has occurred.";

	$output = 'Database error: ' . $e->getMessage().
		' in ' . $e->getFile() . ':' . $e->getLine(); //错误信息 在 文件名：行数
}

include  __DIR__ . '/../templates/layout.html.php';
