<?php
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

	public function searchEvents($searchQuery)
	{
		$searchQuery = '%' . $searchQuery . '%';

		$sql = "SELECT * FROM events WHERE title LIKE :searchQuery";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function searchCategory($searchQuery)
	{
		$searchQuery = '%' . $searchQuery . '%';

		$sql = "SELECT * FROM category WHERE category_name LIKE :searchQuery";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function searchUsers($searchQuery)
	{
		$searchQuery = '%' . $searchQuery . '%';

		$sql = "SELECT * FROM users WHERE (userId LIKE :searchQuery OR first_name LIKE :searchQuery OR last_name LIKE :searchQuery) AND user_role = 'USER'";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function searchAdmins($searchQuery)
	{
		$searchQuery = '%' . $searchQuery . '%';

		$sql = "SELECT * FROM users WHERE (userId LIKE :searchQuery OR first_name LIKE :searchQuery OR last_name LIKE :searchQuery) AND user_role = 'ADMIN'";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':searchQuery', $searchQuery, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function join($query, $values)
	{
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);
		return $stmt->fetchAll();
	}
}
