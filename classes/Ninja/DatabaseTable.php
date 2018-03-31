<?php
namespace Ninja;

class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $constructorArgs;

    public function __construct(\PDO $pdo, string $table, string $primaryKey,
                                                string $className = '\stdClass', array $constructorArgs = []){// '\'forgotten caused errors
        $this->pdo = $pdo; //NO $this->$pdo!!!!
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
    }

    private function query($sql, $parameters = []) {
		$query = $this->pdo->prepare($sql);
		$query->execute($parameters);
		return $query;
	}

    public function total ($field = null, $value = null){
    	$sql = 'SELECT COUNT(*) FROM `' . $this->table . '`';
        $parameters = [];
        if (!empty($field)){
            $sql .= ' WHERE `' . $field . '` = :value';
            $parameters = array(':value' => $value);
        }

    	$query = $this->query($sql, $parameters);
    	$row = $query->fetch();
    	return $row[0];
    }

    public function findById ($pkv){
    	$sql = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :pkv';
    	$parameters = array(':pkv' => $pkv);
    	$query = $this->query($sql, $parameters);
    	return $query->fetchObject($this->className, $this->constructorArgs);
    }

    public function find($column, $value, $orderBy = null, $limit = null, $offset = null){
        $sql = 'SELECT * FROM `' . $this->table . '` WHERE `' . $column . '` = :value';
        $parameters = array(':value' => $value);
        if ($orderBy != null){
            $sql .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != null){
            $sql .= ' LIMIT ' . $limit;
        }
        if ($offset != null){
            $sql .= ' OFFSET ' . $offset;
        }
        $query = $this->query($sql, $parameters);
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    private function insert ($fields){
    	$sql = 'INSERT INTO `' . $this->table . '` (';
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

    	$fields = $this->processDates($fields);

    	$this->query($sql, $fields);

        return $this->pdo->lastInsertId();
    }

    private function update ($fields){
    	$sql = 'UPDATE `' . $this->table . '` SET ';
    	foreach ($fields as $key => $value) {
    		$sql .= '`' . $key . '` = :' . $key . ',';
    	}
    	$sql = rtrim($sql, ',');
    	$sql .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        //Set the :primaryKey variable
    	$fields['primaryKey'] = $fields[$this->primaryKey];  //$fields['id']
    	$fields = $this->processDates($fields);

    	$this->query($sql, $fields);
    }

    public function delete($pkv){
    	$parameters = array(':pkv' => $pkv);
    	$this->query('DELETE FROM  `' . $this->table .
            '` WHERE  `' . $this->primaryKey . '` = :pkv', $parameters);
    }

    public function findAll($orderBy = null, $limit = null, $offset = null){
        $sql = 'SELECT * FROM `' . $this->table . '`';
        if ($orderBy != null){
            $sql  .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != null){
            $sql .= ' LIMIT ' . $limit;
        }
        if ($offset != null){
            $sql .= ' OFFSET ' . $offset;
        }
        $result = $this->query($sql);
    	return $result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    private function processDates ($fields){
    	foreach ($fields as $key => $value) {
    		if ($value instanceof \DateTime){
    			$fields[$key] = $value->format('Y-m-d');
    		}
    	}
    	return $fields;
    }

    public function save($fields){
        $entity = new $this->className(...$this->constructorArgs);

    	try {
    		if ($fields[$this->primaryKey] == ''){
    			$fields[$this->primaryKey] = null;
    		}
            //set the paimary key
    		$insertId = $this->insert($fields);
            $entity->{$this->primaryKey} = $insertId;  //上两行未添加，导致错误
    	} catch (\PDOException $e) {
    		$this->update($fields);
    	}

        foreach ($fields as $key => $value) {
            if (!empty($value)){
                $entity->$key = $value; //!!!$entity->$...
            }
        }
        return $entity;
    }

	public function deleteWhere($column, $value){
        $sql = 'DELETE FROM `' . $this->table . '` WHERE `' . $column . '` = :value';
        $parameters = array(':value' => $value);
        $this->query($sql, $parameters);
    }
}
