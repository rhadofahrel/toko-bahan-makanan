<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class User
{
    private static string $storageFile = 'users.json';

    // Load semua user dari JSON
    public static function loadData(): array
    {
        if (!Storage::exists(self::$storageFile)) {
            return [];
        }
        $content = Storage::get(self::$storageFile);
        return json_decode($content, true) ?: [];
    }

    // Simpan data user ke JSON
    public static function saveData(array $users): void
    {
        Storage::put(self::$storageFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    // Cari user berdasarkan email
    public static function findByEmail(string $email): ?array
    {
        $users = self::loadData();
        foreach ($users as $user) {
            if (is_array($user) && isset($user['email'])) {
                if (trim(strtolower($user['email'])) === trim(strtolower($email))) {
                    return $user;
                }
            }
        }
        return null;
    }

    // Cari user berdasarkan ID
    public static function findById(string $id): ?array
    {
        $users = self::loadData();
        $user = $users[$id] ?? null;
        return is_array($user) ? $user : null;
    }

    // Cek apakah email sudah ada
    public static function emailExists(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }

    // Buat user baru
    public static function create(array $data): array
    {
        $users = self::loadData();
        $id    = (string) (count($users) + 1);

        // Pastikan id unik
        while (isset($users[$id])) {
            $id = (string) ((int) $id + 1);
        }

        $user = [
            'id'         => $id,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'role'       => $data['role'] ?? 'customer',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        $users[$id] = $user;
        self::saveData($users);

        return $user;
    }

    // Semua user
    public static function all(): array
    {
        return array_values(self::loadData());
    }
}
