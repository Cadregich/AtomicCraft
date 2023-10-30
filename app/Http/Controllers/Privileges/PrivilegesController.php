<?php

namespace App\Http\Controllers\Privileges;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use App\Models\Server;
use Illuminate\Http\Request;

class PrivilegesController extends Controller
{
    public function getPrivilegesData(Request $request) {
        $server = $request->input('server');
        $serverId = Server::select('id')->where('server_name', $server)->first()['id'];
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
