<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

interface IBasics
{
    /**
     * Insertar un nuevo registro en la base de datos.
     *
     * @param array $data Datos a insertar, en formato clave => valor.
     * @return int|null ID del registro insertado o null si falla.
     */
    public function create(array $data): ?int;

    /**
     * Obtener un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return array|null Datos del registro o null si no se encuentra.
     */
    public function findById(int $id): ?array;

    /**
     * Obtener todos los registros con soporte para filtros y paginación.
     *
     * @param array $filters Filtros opcionales para la consulta.
     * @param int|null $limit Límite de resultados.
     * @param int|null $offset Desplazamiento para paginación.
     * @return array Lista de registros.
     */
    public function findAll(array $filters = [], ?int $limit = null, ?int $offset = null): array;

    /**
     * Actualizar un registro por su ID.
     *
     * @param int $id ID del registro a actualizar.
     * @param array $data Datos a actualizar, en formato clave => valor.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function update(int $id, array $data): bool;

    /**
     * Eliminar un registro por su ID.
     *
     * @param int $id ID del registro a eliminar.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function delete(int $id): bool;

    /**
     * Contar registros en la tabla con filtros opcionales.
     *
     * @param array $filters Filtros opcionales para la consulta.
     * @return int Número total de registros.
     */
    public function count(array $filters = []): int;
}