<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/Laundry');

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rupiah($number): string
{
    return 'Rp ' . number_format((float)$number, 0, ',', '.');
}

function redirect(string $page): void
{
    header('Location: index.php?page=' . $page);
    exit;
}

function redirect_url(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function flash(): void
{
    if (!empty($_SESSION['flash'])) {
        $type = e($_SESSION['flash']['type']);
        $message = e($_SESSION['flash']['message']);
        echo "<div class=\"alert alert-{$type} alert-dismissible fade show\" role=\"alert\">{$message}<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button></div>";
        unset($_SESSION['flash']);
    }
}

function old(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

function current_page(): string
{
    return $_GET['page'] ?? 'login';
}

function selected($value, $expected): string
{
    return (string)$value === (string)$expected ? 'selected' : '';
}

function checked($value, $expected): string
{
    return (string)$value === (string)$expected ? 'checked' : '';
}
