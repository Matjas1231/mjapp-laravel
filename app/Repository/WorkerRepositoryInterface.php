<?php
declare(strict_types=1);

namespace App\Repository;

interface WorkerRepositoryInterface
{
    public function all();
    public function get(int $id);
    public function save(array $workerData);
    public function update(array $workerData);
}
