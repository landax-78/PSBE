<?php
abstract class BaseController
{
    protected mysqli $db;
    protected Auth $auth;

    public function __construct(mysqli $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function validateRequired(array $data, array $fields): array
    {
        $errors = [];
        foreach ($fields as $field => $label) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                $errors[] = $label . ' wajib diisi.';
            }
        }
        return $errors;
    }
}
