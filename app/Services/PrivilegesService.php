<?php

namespace App\Services;

use App\Models\Privilege;
use App\Models\Server;
use App\Models\User;

class PrivilegesService
{
    public function getPrivilegesCapabilitiesAndTitles($serverId)
    {
        return Privilege::select('title', 'capabilities')->where('server_id', $serverId)->get();
    }

    public function getServerIdByName($serverName)
    {
        return Server::select('id')->where('server_name', $serverName)->value('id');
    }

    public function getTableCapabilitiesData($capabilitiesText, $privilegesData)
    {
        $result = [];

        foreach ($capabilitiesText as $textRu => $textRuValue) {
            $privilegesCapabilities = [];
            foreach ($privilegesData as $privilege) {
                $capabilities = json_decode($privilege['capabilities'], true);
                $privilegesCapabilities[$privilege['title']] = $capabilities[$textRuValue];
            }
            $result[$textRu] = $privilegesCapabilities;
        }

        return $result;
    }

    public function getPrivilegeIdAndPrice($privilegeTitle, $serverId)
    {
        return Privilege::select('id', 'price')->where('title', $privilegeTitle)
            ->where('server_id', $serverId)->first();
    }

    public function getUserPrivilegeTitleAndPrice($userId) {
        $privilegeId = User::select('privilege_id')->where('id', $userId)->value('privilege_id');
        return Privilege::select('title', 'price')->where('id', $privilegeId)->first();
    }

    public function buyPrivililege($userBalance, $userId, $privilegeData, $userPrivilegeData)
    {
        if ($userBalance < $privilegeData->price) {
            throw new \Exception('Недостаточно монет для покупки привилегии ' . $privilegeData->title, 402);
        } elseif ($userPrivilegeData->price >= $privilegeData->price) {
            if ($userPrivilegeData->title === $privilegeData->title) {
                throw new \Exception('У вас уже есть привилегия ' . $userPrivilegeData->title, 409);
            } else {
                throw new \Exception('У вас уже есть привилегия лучше этой', 409);
            }
        } else {
            $newBalance = $userBalance - $privilegeData->price;
            User::where('id', $userId)->update([
                'balance' => $newBalance,
                'privilege_id' => $privilegeData->id
            ]);
        }
    }
}
