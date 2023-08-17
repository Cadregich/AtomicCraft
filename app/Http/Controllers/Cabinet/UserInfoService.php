<?php

namespace App\Services\Cabinet;

use App\Models\Payment;
use App\Models\Privilege;
use App\Models\User;

class UserInfoService
{
    public function getUserData() {
        return User::where('id', auth()->id())
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
            ->where('user_id', auth()->id())->get();
    }

    public function getCapabilitiesFromTotalDonate($totalDonate): array {
        return [
            'nickChange' => $totalDonate >= 100,
            'skinAndCapeChange' => $totalDonate >= 250,
            'hdSkinsAndCapeUpload' => $totalDonate >= 400
        ];
    }
}
