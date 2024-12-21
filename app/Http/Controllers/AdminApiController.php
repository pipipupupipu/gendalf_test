<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\PartnerRight;
use App\Models\User;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use InvalidArgumentException;


class AdminApiController extends Controller
{
    public function create(Request $request, $tableName)
    {
        $model = $this->getModel($tableName);
        $data = $request->all();
        $filteredData = $this->filterData($data, $model);

        if (empty($filteredData)) {
            throw new InvalidArgumentException('Невалидные данные', 400);
        }

        $record = $model::query()->create($filteredData);

        return response()->json([
            'record' => $record,
        ], 201);
    }

    public function update(Request $request, $tableName, $id)
    {
        $model = $this->getModel($tableName);
        $data = $request->all();
        $filteredData = $this->filterData($data, $model);

        if (empty($filteredData)) {
            throw new InvalidArgumentException('Невалидные данные', 400);
        }

        $record = $model::query()->find($id);

        if (!$record) {
            throw new InvalidArgumentException('Запись не найдена', 204);
        }

        $record->update($filteredData);

        return response()->json([
            'record' => $record,
        ], 200);
    }

    public function delete($tableName, $id)
    {
        $model = $this->getModel($tableName);
        try {
            $model::query()->find($id)->delete();
        } catch (\Error $e) {
            throw new InvalidArgumentException('Запись не найдена', 204);
        }
    }

    public function get(Request $request, $tableName, $id = null)
    {
        $query = $this->getQuery($tableName);

        $filters = $request->query();
        if ($id) {
            $filters['id'] = $id;
        }
        return $this->buildResponse($filters, $query, $tableName);
    }

    protected function getQuery($tableName)
    {
        $model = $this->getModel($tableName);
        return $model::query();
    }

    private function getModel($tableName): Model
    {
        $models = [
            'items' => Item::class,
            'users' => User::class,
            'categories' => Category::class,
            'group' => Group::class,
            'partner_rights' => PartnerRight::class
        ];

        if (!array_key_exists($tableName, $models)) {
            throw new InvalidArgumentException('Неизвестное имя: ' . $tableName, 400);
        }

        return new $models[$tableName];
    }

    private function filterData(array $data, Model $model): array
    {
        $fillable = $model->getFillable();
        return array_intersect_key($data, array_flip($fillable));
    }

    private function applyFilters(&$query, $filters)
    {
        $columns = $query->getModel()->getColumns();

        foreach ($filters as $key => $value) {
            if (!in_array($key, $columns)) {
                throw new InvalidArgumentException('Неизвестный атрибут: ' . $key, 400);
            }
            $query->where($key, $value);
        }
    }

    public function buildResponse($filters, $query, $tableName)
    {
        $perPage = $filters['perPage'] ?? null;
        $page = $filters['page'] ?? 1;

        unset($filters['perPage'], $filters['page']);

        $this->applyFilters($query, $filters);

        if ($perPage) {
            $records = $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            $records = $query->get();
        }

        $desired = $records->map(function ($item) {
            return $item->toAPIArray();
        });

        if ($desired->isEmpty()) {
            throw new Exception('Ничего не найдено', 204);
        }

        $response = [
            $tableName => $desired,
        ];

        if ($perPage) {
            $response['pagination'] = [
                'current_page' => $records->currentPage(),
                'total_pages' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
            ];
        }

        return response()->json($response);
    }
}
