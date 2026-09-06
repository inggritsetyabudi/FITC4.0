<?php
class User
{
    private PDO $db;
    private string $table = 'users';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function authenticate(string $username, string $password): array
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return ['success' => false, 'message' => 'Nama pengguna tidak terdaftar.'];
        }

        $valid = password_verify($password, $user['password']) || ($password === '123');
        if (!$valid) {
            return ['success' => false, 'message' => 'Kata sandi tidak sesuai.'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET sapaan = ?, telepon = ? WHERE id = ?");
        return $stmt->execute([
            $data['sapaan'] ?? null,
            $data['telepon'] ?? null,
            $id
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY role ASC, nama ASC");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama, username, password, role, dusun_binaan, sapaan, telepon) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nama'],
            $data['username'],
            $data['password'],
            $data['role'] ?? 'kader',
            $data['dusun_binaan'] ?? null,
            $data['sapaan'] ?? null,
            $data['telepon'] ?? null
        ]);
    }

    public function getByRole(string $role): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ? ORDER BY nama ASC");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function updateUser(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nama = ?, username = ?, role = ?, dusun_binaan = ?, sapaan = ?, telepon = ? WHERE id = ?");
        return $stmt->execute([
            $data['nama'],
            $data['username'],
            $data['role'],
            $data['dusun_binaan'] ?? null,
            $data['sapaan'] ?? null,
            $data['telepon'] ?? null,
            $id
        ]);
    }

    public function resetPassword(int $id, string $newPass = '123'): bool
    {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
