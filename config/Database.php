<?php
class Database
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'db_laundry';
    private ?mysqli $connection = null;

    public function getConnection(): mysqli
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );
            $this->connection->set_charset('utf8mb4');
            return $this->connection;
        } catch (mysqli_sql_exception $e) {
            if ((int)$e->getCode() === 1049) {
                throw new RuntimeException(
                    "Database '{$this->database}' belum ditemukan. Import file database/laundry.sql lewat CMD XAMPP terlebih dahulu."
                );
            }
            throw new RuntimeException('Koneksi database gagal: ' . $e->getMessage());
        }
    }
}
