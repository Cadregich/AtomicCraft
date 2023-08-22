<?php

namespace App\Services\Cabinet;

use App\Models\Payment;
use App\Models\Privilege;
use App\Models\User;

class UserDataService
{
    public function getUserData($userId) {
        return User::where('id', $userId)
            ->select('name', 'privilege_id', 'last_game_login', 'email', 'created_at')->first();
    }

    public function getPrivilegeTitle($userData) {
        return $userData->privilege_id ? Privilege::findOrFail($userData->privilege_id)->title : 'Игрок';
    }

    public function getLastGameLoginDate($userData) {
        return $userData->last_game_login ? $userData->last_game_login->format('d.m.Y') : 'Никогда';
    }

    public function getAllDonates($userId) {
        return Payment::select('amount', 'currency')
            ->where('user_id', $userId)->get();
    }

    public function getCapabilitiesFromTotalDonate($totalDonate): array {
        return [
            'nickChange' => $totalDonate >= 100,
            'capeChange' => $totalDonate >= 250,
            'hdSkinsUpload' => $totalDonate >= 400
        ];
    }

    public function maskEmail($email): string
    {
        [$emailName, $emailDomain] = explode('@', $email);

        $nameLength = strlen($emailName);
        if ($nameLength > 6) {
            $maskEmailName = substr($emailName, 0, 2) . '...' . substr($emailName, -3);
        } elseif ($nameLength === 6) {
            $maskEmailName = substr($emailName, 0, 1) . '...' . substr($emailName, -3);
        } elseif ($nameLength === 5) {
            $maskEmailName = $emailName[0] . '...' . substr($emailName, -2);
        } elseif ($nameLength === 4) {
            $maskEmailName = '...' . substr($emailName, -2);
        } else {
            $maskEmailName = '...';
        }

        return $maskEmailName . '@' . $emailDomain;
    }
}
