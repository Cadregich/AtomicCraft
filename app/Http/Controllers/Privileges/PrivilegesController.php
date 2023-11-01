<?php

namespace App\Http\Controllers\Privileges;

use App\Http\Controllers\Controller;
use App\Services\PrivilegesService;
use App\Models\User;
use Illuminate\Http\Request;

class PrivilegesController extends Controller
{
    protected PrivilegesService $privilegesService;

    public function __construct(PrivilegesService $privilegesService)
    {
        $this->privilegesService = $privilegesService;
    }

    public function getCapabilitiesData(Request $request)
    {
        $server = $request->input('server');
        $serverId = $this->privilegesService->getServerIdByName($server);
        $privilegesData = $this->privilegesService->getPrivilegesCapabilitiesAndTitles($serverId);
        $capabilitiesText = $this->configureTextCapabilities();

        $tableCapabilitiesData = $this->privilegesService->getTableCapabilitiesData($capabilitiesText, $privilegesData);
        $privilegesTitles = $privilegesData->pluck('title')->all();

        return ['capabilities' => $tableCapabilitiesData, 'privileges' => $privilegesTitles];
    }

    public function buy(Request $request)
    {
        $server = $request->input('server');
        $privilege = $request->input('privilege');
        $userId = session('user')['id'];
        $userBalance = User::select('balance')->where('id', $userId)->value('balance');
        $serverId = $this->privilegesService->getServerIdByName($server);

        $privilegeActualData = $this->privilegesService->getPrivilegeIdAndPrice($privilege['title'], $serverId);
        $privilegeActualData['title'] = $privilege['title'];

        try {
            $this->privilegesService->buyPrivililege($userBalance, $userId, $privilegeActualData);
            return response()->json(['message' => 'Привилегия ' . $privilege['title'] . ' успешно куплена.'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 402);
        }
    }

    private function configureTextCapabilities()
    {
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
