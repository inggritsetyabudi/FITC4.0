<?php

class User
{
    public function __construct(private PDO $db) {}

    public function authenticate(string $username, string $password): array
    {
        $stmt = $this->db->prepare("SELECT u.id, u.nama, u.username, u.password, u.role, u.telepon, u.created_at, d.nama AS dusun_binaan
                FROM users u LEFT JOIN dusun d ON d.id = u.dusun_id
                WHERE u.username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Username atau kata sandi tidak sesuai.'];
        }

        unset($user['password']);
        return ['success' => true, 'user' => $user];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nama, username, password, role, telepon, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT u.id, u.nama, u.username, u.role, u.telepon, u.created_at, d.nama AS dusun_binaan
                FROM users u LEFT JOIN dusun d ON d.id = u.dusun_id
                ORDER BY u.role, u.nama");
        return $stmt->fetchAll();
    }

    public function updateTelepon(int $id, ?string $telepon): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET telepon = ? WHERE id = ?");
        return $stmt->execute([$telepon, $id]);
    }

    public function updatePassword(int $id, string $passwordBaru): bool
    {
        $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO users (nama, username, password, role, dusun_id, telepon) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nama'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['dusun_id'] ?? null,
            $data['telepon'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
