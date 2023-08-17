<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\UserDataService;
use App\Services\Cabinet\PlayerAssetsService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CabinetController extends Controller
{
    private $totalDonate;
    protected $userDataService;
    protected $paymentService;
    protected $playerAssets;

    public function __construct(PlayerAssetsService $playerAssetsService, UserDataService $userDataService, PaymentService $paymentService)
    {
        $this->userDataService = $userDataService;
        $this->paymentService = $paymentService;
        $this->playerAssets = $playerAssetsService;
    }

    public function __invoke(Request $request)
    {
        $userName = $request->user()->name;
        return $this->playerAssets->getSkinAndCapePaths($userName);
    }

    public function getUserInfo(Request $request): array
    {
        $userId = $request->user()->id;
        $userData = $this->userDataService->getUserData($userId);
        $privilegeTitle = $this->userDataService->getPrivilegeTitle($userData);
        $lastGameLoginDate = $this->userDataService->getLastGameLoginDate($userData);
        $registrationDate = $userData->created_at->format('d.m.Y');
        $capabilitiesFromTotalDonate = $this->getCapabilitiesFromAllDonates($userId);
        $userName = $userData->name;
        $userEmail = $this->userDataService->maskEmail($userData->email);
        return compact('userName', 'userEmail', 'privilegeTitle', 'lastGameLoginDate', 'capabilitiesFromTotalDonate', 'registrationDate');
    }


    private function getCapabilitiesFromAllDonates($userId): array
    {
        $allDonates = $this->userDataService->getAllDonates($userId);
        $totalDonates = $this->getTotalDonateInCoins($allDonates);
        return $this->userDataService->getCapabilitiesFromTotalDonate($totalDonates);
    }

    private function getTotalDonateInCoins($allDonates) {
        $totalDonate = 0;

        foreach ($allDonates as $donate) {
            $totalDonate += $this->paymentService->currencyToCoins($donate->amount, $donate->currency);
        }
        return $totalDonate;
    }
}
