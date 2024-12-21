<?php

namespace App\Http\Controllers;

use App\Models\PartnerRight;
use App\Models\User;
use Illuminate\Http\Request;

class PartnerApiController extends AdminApiController
{

    public function get(Request $request, $tableName, $id = null)
    {
        $filters = $request->query();
        $username = $filters['username'] ?? null;
        $password = $filters['password'] ?? null;

        $partner = $this->authenticatePartner($username, $password);
        unset($filters['username'], $filters['password']);

        $allowedCategories = $this->getAllowedCategories($partner->id);


        $query = $this->getQuery($tableName);
        $this->applyCategoryFilter($query, $tableName, $allowedCategories);

        if ($id) {
            $filters['id'] = $id;
        }
        return $this->buildResponse($filters, $query ?? $this->getQuery($tableName), $tableName);
    }

    private function authenticatePartner($username, $password)
    {
        if (!$username || !$password) {
            throw new \Exception('Логин и пароль обязательны для входа', 400);
        }

        $partner = User::query()
            ->where('name', $username)
            ->where('password', md5($password))
            ->first();

        if (!$partner) {
            throw new \Exception('Неверный логин или пароль', 403);
        }

        return $partner;
    }

    private function getAllowedCategories($userId)
    {
        $rights = PartnerRight::query()->where('user_id', $userId)->get();
        return array_map(fn($right) => $right['category_id'], $rights->toArray());
    }

    private function applyCategoryFilter($query, $tableName, $allowedCategories)
    {
        if ($tableName == 'items') {
            $query->whereIn('category_id', $allowedCategories);
        } elseif ($tableName == 'categories') {
            $query->whereIn('id', $allowedCategories);
        }
    }


}
