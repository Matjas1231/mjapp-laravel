<?php
declare(strict_types=1);

namespace App\Repository\Software;

use App\Models\Software;
use App\Repository\SoftwareRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SoftwareRepository implements SoftwareRepositoryInterface
{
    private Software $softwareModel;

    public function __construct(Software $softwareModel)
    {
        $this->softwareModel = $softwareModel;
    }

    public function all()
    {
        return $this->softwareModel
                    ->with('worker')
                    ->paginate(25);
    }

    public function filterBy(?array $filters)
    {
        if (!empty($filters)) {
            $softwares = $this->softwareModel->query();

            if (!empty($filters['filter'])) {
                $fullname = $filters['filter'];
                $softwares->whereHas('worker', function($q) use($fullname) {
                    $q->where(DB::raw('("name" || " " || "surname")'), 'LIKE', "%$fullname%");
                })->get();
            }

            if (!empty($filters['producer'])) {
                $producer = $filters['producer'];
                $softwares->where('producer', 'LIKE', "%$producer%")->get();
            }

            if (!empty($filters['name'])) {
                $name = $filters['name'];
                $softwares->where('name', 'LIKE', "%$name%")->get();
            }

            if (!empty($filters['serialnumber'])) {
                $serialNumber = $filters['serialnumber'];
                $softwares->where('serial_number', 'LIKE', "%$serialNumber%")->get();
            }

            return $softwares->with('worker')->paginate(25);
        } else {
            return $this->softwareModel->with('worker')->paginate(25);
        }
    }

    public function searchSoftware(array $filters)
    {
        $software = $this->softwareModel->query();

        if (!is_null($filters['filterName'])) {
            $software->whereHas('worker', function ($q) use($filters) {
                $q->where(DB::raw('("name" || " " || "surname")'), 'LIKE', "%{$filters['filterName']}%");
            });
        }

        if (!is_null($filters['filterProd'])) $software->where('producer', 'LIKE', "%{$filters['filterProd']}%");
        if (!is_null($filters['filterNa'])) $software->where('name', 'LIKE', "%{$filters['filterNa']}%");
        if (!is_null($filters['filterSn'])) $software->where('serial_number', 'LIKE', "%{$filters['filterSn']}%");

        $software->with('worker', function ($q) {
            $q->select(['id', 'name', 'surname']);
        });

        return $software->get(['id', 'producer', 'name', 'serial_number', 'expiry_date', 'worker_id'])->toArray();
    }

    public function softwareWithoutWorker()
    {
        $software = $this->softwareModel->query();
        $software->where('worker_id', '=', null)
                ->with('worker', fn ($q) => $q->select(['id', 'name', 'surname']));

        return $software->get(['id', 'producer', 'name', 'serial_number', 'expiry_date', 'worker_id'])->toArray();
    }

    public function getSoftware(int $id)
    {
        return $this->softwareModel->find($id);
    }

    public function storeAndReturnId(array $softwareData)
    {
        $software = $this->softwareModel->newInstance();
        $software->producer = $softwareData['producer'];
        $software->serial_number = $softwareData['serial_number'];
        $software->name = $softwareData['name'];
        $software->worker_id = $softwareData['worker_id'];
        $software->description = $softwareData['description'];
        $software->date_of_buy = $softwareData['date_of_buy'] ?? Carbon::now()->format('Y-m-d');
        $software->expiry_date = $softwareData['expiry_date'] ?? 'N/D';

        $software->save();

        return $software->id;
    }

    public function update(array $softwareData)
    {
        $software = $this->softwareModel->find($softwareData['id']);
        $software->producer = $softwareData['producer'];
        $software->serial_number = $softwareData['serial_number'];
        $software->name = $softwareData['name'];
        $software->worker_id = $softwareData['worker_id'];
        $software->description = $softwareData['description'];
        $software->date_of_buy = $softwareData['date_of_buy'] ?? Carbon::now()->format('Y-m-d');
        $software->expiry_date = $softwareData['expiry_date'] ?? 'N/D';

        return $software->save();
    }

    public function delete(int $id)
    {
        return $this->softwareModel->find($id)->delete();
    }

    public function countSoftwares()
    {
        return $this->softwareModel->all()->count();
    }
}
