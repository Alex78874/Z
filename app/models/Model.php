<?php

class Model
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = getPDO(); // Méthode de connexion à la BDD, doit être définie ailleurs
    }

    // Crée un nouvel enregistrement
    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    // Crée un nouvel enregistrement et le retourne
    public function createAndGet(array $data): mixed
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");

        if ($stmt->execute($data)) {
            $lastId = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->execute(['id' => $lastId]);
            return $stmt->fetch();
        }

        return null;
    }

    // Récupère un enregistrement par ID
    public function getById($id): mixed
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Met à jour un enregistrement par ID
    public function update($id, array $data): bool
    {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET {$setClause} WHERE id = :id");
        return $stmt->execute($data);
    }

    // Supprime un enregistrement par ID
    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Récupère tous les enregistrements
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // Récupère des enregistrements avec condition
    public function getWhere(array $conditions): array
    {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$whereClause} ORDER BY id DESC");
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }

    // Récupère des enregistrements avec condition quand null
    public function getWhereNull(array $conditions): array
    {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key IS NULL", array_keys($conditions)));
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$whereClause} ORDER BY id DESC");
        return $stmt->execute() ? $stmt->fetchAll() : [];
    }

    public function getWhereCount(array $conditions): int
    {
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$whereClause}");
        $stmt->execute($conditions);
        return $stmt->fetchColumn();
    }

    public function getLastInserted(): mixed
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} WHERE id = LAST_INSERT_ID()");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAfterId($lastId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id > :lastId ORDER BY id DESC");
        $stmt->execute(['lastId' => $lastId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
