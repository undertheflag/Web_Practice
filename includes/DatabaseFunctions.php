<?php
function query ($pdo, $sql, $parameters = []) {
	$query = $pdo->prepare($sql);
	$query->execute($parameters);
	return $query;
}

function processDates ($fields){
	foreach ($fields as $key => $value) {
		if ($value instanceof DateTime){
			$fields[$key] = $value->format('Y-m-d');
		}
	}
	return $fields;
}

/*function totalJokes($pdo){ //error processing is practical
	try{
		$sql = 'SELECT COUNT(*) FROM `joke`';
		$query = query($pdo, $sql);
		$row = $query->fetch();
		return $row[0];
	} catch(PDOException $e){
		$title = "An error has occurred.";

		$output = 'Database error: ' . $e->getMessage().
			' in ' . $e->getFile() . ':' . $e->getLine();
		}
}*/

function total ($pdo, $table){
	$sql = 'SELECT COUNT(*) FROM `' . $table . '`';
	$query = query($pdo, $sql);
	$row = $query->fetch();
	return $row[0];
}
/*function getJoke($pdo, $id){
	$sql = 'SELECT * FROM `JOKE` WHERE `id` = :id';
	$parameters = array(':id' => $id);
	$query = query($pdo, $sql, $parameters);
	return $query->fetch(); //第一次用fetchAll()，导致edit错误
}*/

function findById ($pdo, $table, $primaryKey, $pkv){
	$sql = 'SELECT * FROM `' . $table . '` WHERE `' . $primaryKey . '` = :pkv';
	$parameters = array(':pkv' => $pkv);
	$query = query($pdo, $sql, $parameters);
	return $query->fetch(); //第一次用fetchAll()，导致edit错误
}

/*function insertJoke ($pdo, $joketext, $authorid){
	$sql = 'INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
			VALUES (:joketext, CURDATE(), :authorid)';
	$parameters = array(':joketext' => $joketext, ':authorid' => $authorid);
	query($pdo, $sql, $parameters);
}*/

/*function insertJoke ($pdo, $fields){
	$sql = 'INSERT INTO `joke` (';
	foreach ($fields as $key => $value) {
		$sql .= '`' . $key . '`,';
	}
	$sql = rtrim($sql, ',');
	$sql .= ') VALUES (';
	foreach ($fields as $key => $value) {
		$sql .= ':' . $key . ',';
	}
	$sql = rtrim($sql, ',');
	$sql .= ')';

	$fields = processDates($fields);

	query($pdo, $sql, $fields);
}*/

function insert ($pdo, $table, $fields){
	$sql = 'INSERT INTO `' . $table . '` (';
	foreach ($fields as $key => $value) {
		$sql .= '`' . $key . '`,';
	}
	$sql = rtrim($sql, ',');
	$sql .= ') VALUES (';
	foreach ($fields as $key => $value) {
		$sql .= ':' . $key . ',';
	}
	$sql = rtrim($sql, ',');
	$sql .= ')';

	$fields = processDates($fields);

	query($pdo, $sql, $fields);
}
/*function updateJoke ($pdo, $jokeid, $joketext, $authorid){
	$sql = 'UPDATE `joke` SET `authorid` = :authorid,
		`joketext` = :joketext WHERE `id` = :id';

	$parameters = array(':authorid' => $authorid,
		':joketext' => $joketext, ':id' => $jokeid);

	query($pdo, $sql, $parameters);
}*/

function update ($pdo, $table, $primaryKey, $fields){
	$sql = 'UPDATE `' . $table . '` SET ';
	foreach ($fields as $key => $value) {
		$sql .= '`' . $key . '` = :' . $key . ',';
	}
	$sql = rtrim($sql, ',');
	$sql .= ' WHERE `' . $primaryKey . '` = :primaryKey';

	$fields['primaryKey'] = $fields[$primaryKey];  //$fields['id']
	$fields = processDates($fields);

	query($pdo, $sql, $fields);
}

/* function query($pdo, $sql, $parameters = []){
	$query = $pdo->prepare($sql);
	foreach ($parameters as $key => $value){
		$query->bindValue($key, $value);
	}
	$query->execute();
	return $query;
} */

/*function deleteJoke($pdo, $id){
	$parameters = array(':id' => $id);

	query($pdo, 'DELETE FROM `joke` WHERE `id` = :id', $parameters);
}*/

function delete($pdo, $table, $primaryKey, $pkv){
	$parameters = array(':pkv' => $pkv);
	query($pdo, 'DELETE FROM  `' . $table . '` WHERE  `' . $primaryKey . '` = :pkv', $parameters);
}

/*function allJokes($pdo){
	$jokes = query($pdo, 'SELECT `joke`.`id`, `joketext`,
		`name`,`email` FROM `joke` INNER JOIN `author`
		ON `authorid` = `author`.`id`');

	return $jokes->fetchAll();
}*/

function findAll($pdo, $table){
	$result = query($pdo, 'SELECT * FROM `' . $table . '`');
	return $result->fetchAll();
}

function save($pdo, $table, $primaryKey, $fields){
	try {
		if ($fields[$primaryKey] == ''){
			$fields[$primaryKey] = null;
		}
		insert($pdo, $table, $fields);
	} catch (PDOException $e) {
		update($pdo, $table, $primaryKey, $fields);
	}
}
