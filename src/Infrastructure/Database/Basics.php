<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use DI\ContainerBuilder;
use PDO;
use PDOStatement;
use App\Application\Actions\ActionPayload;



class Basics
{
    protected PDO $db;
    protected string $table;
    protected PDOStatement $stmt;

    public function __construct(string $table)
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Set up settings
        $settings = require __DIR__ . '/../../../app/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../../app/dependencies.php';
        $dependencies($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        try {
            $this->db = $container->get('db'); // Obtener el objeto PDO
            $this->table = $table; // Nombre de la tabla asociada
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
    
    public function __invoke(array $arrayData){

        if(isset($arrayData)){
            if(isset($arrayData["table"])){
                $this->table = $arrayData["table"];
            }
            else if(isset($arrayData["db"])){
                $this->db = $arrayData["db"];
            }
        }
    }

    /**
     * Ejecutar una consulta SQL con parámetros.
     *
     * @param string $sql Consulta SQL a ejecutar.
     * @param array $params Parámetros para la consulta.
     * @return ActionPayload|null Resultado de la consulta o null si falla.
     */
    protected function executeQuery(string $typeQuery, string $sql, array $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);

            $stmt->execute($params);

            $this->stmt = $stmt;

            $responseStmt = $this->processResponseQuery($typeQuery);
            return $responseStmt;
        } catch (PDOException $e) {
            error_log("Error en executeQuery: " . $e->getMessage());
            return null;
        }
    }

    protected function processResponseQuery($typeQuery){

        $stmtError = $this->stmt->errorInfo();
        if ($stmtError[0] == 0 && !$stmtError[1] && !$stmtError[2]){
            $objResponse = new ActionPayload(200, null, null);

            switch ($typeQuery) {
                case 'select':{
                    $data = $this->stmt->fetchAll(PDO::FETCH_OBJ);
                    $objResponse->set(200, $data, null);
                    break;
                }
                case 'insert' || 'update' || 'delete':{
                    $data = new \stdClass();
                    $data->afectedRow = $this->stmt->rowCount();
                    $objResponse->set(200, $data, null);
                    break;
                }
                case 'create' || 'drop':{
                    $data = new \stdClass();
                    $data->errorInfo = $this->stmt->errorInfo();
                    $objResponse->set(200, $data, null);
                    break;
                }
                default:
                    return $objResponse;
            }

            return $objResponse;

        }

        return new ActionPayload(500, null, new ActionError(SERVER_ERROR, SERVER_ERROR));

    }

    /**
     * Insertar un nuevo registro en la base de datos.
     *
     * @param array $data Datos a insertar, en formato clave => valor.
     * @return int|null ID del registro insertado o null si falla.
     */
    public function create(array $data): ?int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $responseStmt = $this->executeQuery("insert", $sql, $data);
        if ($responseStmt->getStatusCode() == 200) {
            return (int)$this->db->lastInsertId();
        }
        return null;
    }

    /**
     * Obtener un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return array|null Datos del registro o null si no se encuentra.
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->executeQuery("select", $sql, ['id' => $id]);

        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return null;
    }
}