<?php

namespace App;

class MasterClass
{
    private array $registrations = [];

    public function register(string $name, int $age, string $theme, bool $materialsIncluded, string $format): array
    {
        $errors = [];

        if (empty(trim($name))) {
            $errors[] = 'Имя не может быть пустым';
        }

        if ($age < 5 || $age > 120) {
            $errors[] = 'Возраст должен быть от 5 до 120 лет';
        }

        if (empty(trim($theme))) {
            $errors[] = 'Тема мастер-класса не указана';
        }

        if (!in_array($format, ['online', 'offline'])) {
            $errors[] = 'Неверный формат участия';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $registration = [
            'id' => count($this->registrations) + 1,
            'name' => htmlspecialchars($name),
            'age' => $age,
            'theme' => htmlspecialchars($theme),
            'materials_included' => $materialsIncluded,
            'format' => $format
        ];

        $this->registrations[] = $registration;

        return ['success' => true, 'data' => $registration];
    }

    public function getAll(): array
    {
        return $this->registrations;
    }

    public function getCount(): int
    {
        return count($this->registrations);
    }

    public function getByFormat(string $format): array
    {
        return array_filter($this->registrations, function ($reg) use ($format) {
            return $reg['format'] === $format;
        });
    }
}