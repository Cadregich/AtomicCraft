<?php

namespace App\Http\Controllers\Privileges;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Request;

class PrivilegesController extends Controller
{
    public function getCapabilitiesData(Request $request) {
        $server = $request->input('server');
        $serverId = $this->getServerIdByName($server);
        $privilegesData = Privilege::select('title', 'capabilities')->where('server_id', $serverId)->get();

        $result = [];
        $capabilitiesText = $this->configureTextCapabilities();

        foreach ($capabilitiesText as $textRu => $textRuValue) {
            $privilegesCapabilities = [];
            foreach ($privilegesData as $privilege) {
                $capabilities = json_decode($privilege['capabilities'], true);
                $privilegesCapabilities[$privilege['title']] = $capabilities[$textRuValue];
            }
            $result[$textRu] = $privilegesCapabilities;
        }

        $privilegesTitles = $privilegesData->pluck('title')->all();

        return ['capabilities' => $result, 'privileges' => $privilegesTitles];
    }

    public function getPrivilegesData(Request $request) {
        $server = $request->input('server');
        $serverId = $this->getServerIdByName($server);
        return Privilege::select('title', 'price')->where('server_id', $serverId)->get();
    }

    public function buy(Request $request)
    {
        $server = $request->input('server');
        $privilege = $request->input('privilege');
        $userId = session('user')['id'];
        $userBalance = User::select('balance')->where('id', $userId)->value('balance');
        $serverId = Server::select('id')->where('server_name', $server)->value('id');
        $privilegeActualData = Privilege::select('id', 'price')->where('title', $privilege['title'])
            ->where('server_id', $serverId)->first();

        if ($userBalance < $privilegeActualData->price) {
            return response()->json(['error' => 'Недостаточно монет для покупки привилегии ' . $privilege['title']], 402);
        } else {
            $newBalance = $userBalance - $privilegeActualData->price;
            User::where('id', $userId)->update([
                'balance' => $newBalance,
                'privilege_id' => $privilegeActualData->id
            ]);

            return response()->json(['message' => 'Привилегия ' . $privilege['title'] . ' успешно куплена.'], 200);
        }
    }

    private function getServerIdByName($serverName) {
        return Server::select('id')->where('server_name', $serverName)->first()['id'];
    }

    private function configureTextCapabilities() {
        return [
            'Варпы' => 'warps',
            'Приваты' => 'privates',

            '/kit vip' => 'kitVip',
            '/kit resources' => 'kitResources',
            '/kit food' => 'kitFood',
            'Эндер сундук /chest' => 'enderChest',
            'Верстак /craft' => 'craftingBench',
            'Сохранение exp после смерти' => 'saveExp',

            '/kit extra' => 'kitExtra',
            'Сохранение инвентаря после смерти' => 'saveInventory',
            'Заход на переполненный сервер' => 'enterToFullServer',
            '/kit exp' => 'kitExp',
            'Восстановление голода' => 'eat',

            '/kit absolute' => 'kitAbsolute',
            'Полёт' => 'fly',
            '/kit resources2' => 'kitResources2',
            'Восстановление здоровья' => 'heal',
        ];
    }
}
