<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\UserInfoService;
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

    public function __construct(PlayerAssetsService $playerAssetsService)
    {
//        $this->userDataService = $userDataService;
//        $this->paymentService = $paymentService;
        $this->playerAssets = $playerAssetsService;
    }

    public function __invoke(Request $request)
    {
        $userName = $request->user()->name;
//        $userData = $this->userDataService->getUserData();
//        $privilegeTitle = $this->userDataService->getPrivilegeTitle($userData);
//        $lastGameLoginDate = $this->userDataService->getLastGameLoginDate($userData);
//        $registrationDate = $userData->created_at->format('d.m.Y');
//        $capabilitiesFromTotalDonate = $this->getCapabilitiesFromAllDonates();
        $playerAssets = $this->playerAssets->getSkinAndCapePaths($userName);
        return $playerAssets;
//        return view('cabinet', compact('userData', 'privilegeTitle', 'lastGameLoginDate', 'capabilitiesFromTotalDonate', 'registrationDate'));
    }

    private function getCapabilitiesFromAllDonates(): array
    {
        $allDonates = $this->userDataService->getAllDonates(auth()->id());
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
