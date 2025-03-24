<?php
session_start();
class DatabaseTable
{
	private $pdo;
	private $table;
	private $primaryKey;

	public function __construct($pdo, $table, $primaryKey)
	{
		$this->pdo = $pdo;
		$this->table = $table;
		$this->primaryKey = $primaryKey;
	}

	public function find($field, $value)
	{
		$stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value');

		$criteria = [
			'value' => $value
		];
		$stmt->execute($criteria);

		return $stmt->fetchAll();
	}


	public function findAll()
	{
		$stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table);

		$stmt->execute();

		return $stmt->fetchAll();
	}

	public function insert($record)
	{
		$keys = array_keys($record);

		$values = implode(', ', $keys);
		$valuesWithColon = implode(', :', $keys);

		$query = 'INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';

		$stmt = $this->pdo->prepare($query);

		$stmt->execute($record);
	}

	public function delete($id)
	{
		$stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id');
		$criteria = [
			'id' => $id
		];
		$stmt->execute($criteria);
	}

	public function save($record)
	{
		if (empty($record[$this->primaryKey])) {
			unset($record[$this->primaryKey]);
		}

		try {
			$this->insert($record);
		} catch (Exception $e) {
			$this->update($record);
		}
	}

	public function update($record)
	{

		$query = 'UPDATE ' . $this->table . ' SET ';

		$parameters = [];
		foreach ($record as $key => $value) {
			$parameters[] = $key . ' = :' . $key;
		}

		$query .= implode(', ', $parameters);
		$query .= ' WHERE ' . $this->primaryKey . ' = :primaryKey';

		$record['primaryKey'] = $record[$this->primaryKey];

		$stmt = $this->pdo->prepare($query);

		$stmt->execute($record);
	}

	public function search($columns = [], $searchTerm = '', $additionalCondition = '')
	{
		if (empty($columns)) return [];
	
		$searchTerm = '%' . $searchTerm . '%';
		$likeClauses = [];
	
		// Loop through columns and build LIKE clauses for each
		foreach ($columns as $column) {
			$likeClauses[] = "{$column} LIKE :searchTerm";
		}
	
		$whereClause = implode(' OR ', $likeClauses);
	
		if (!empty($additionalCondition)) {
			$whereClause = "($whereClause) AND {$additionalCondition}";
		}
	
		// Build the SQL query with the dynamic where clauses
		$sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
		$stmt->execute();
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function searchEvents($searchTerm)
	{
		return $this->search(['title'], $searchTerm);
	}

	public function searchCategory($searchTerm)
	{
		return $this->search(['category_name'], $searchTerm);
	}

	public function searchUsers($searchTerm)
	{
		return $this->search(['userId', 'first_name', 'last_name'], $searchTerm, "user_role = 'USER'");
	}

	public function searchAdmins($searchTerm)
	{
		return $this->search(['userId', 'first_name', 'last_name'], $searchTerm, "user_role = 'ADMIN'");
	}
	public function join($query, $values)
	{
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);
		return $stmt->fetchAll();
	}
}
