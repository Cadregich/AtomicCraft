<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use App\Services\Cabinet\UserDataService;
use App\Services\Cabinet\PlayerAssetsService;
use App\Services\Coins;
use App\Services\Payment;
use App\Services\PrivilegesService;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    protected UserDataService $userDataService;
    protected Coins $paymentService;
    protected PlayerAssetsService $playerAssets;
    protected PrivilegesService $privilegesService;

    public function __construct(PlayerAssetsService $playerAssetsService, UserDataService $userDataService, Coins $paymentService, PrivilegesService $privilegesService)
    {
        $this->userDataService = $userDataService;
        $this->paymentService = $paymentService;
        $this->playerAssets = $playerAssetsService;
        $this->privilegesService = $privilegesService;
    }

    public function __invoke(Request $request)
    {
        $userName = $request->user()->name;
        return $this->playerAssets->getSkinAndCapePaths($userName);
    }

    public function pay(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $payment = new Payment($amount, $currency);
        $orderId = $payment->generateRandomId();
        \App\Models\Payment::create([
            'user_id' => session('user')['id'],
            'amount' => $amount,
            'currency' => $currency,
            'payment_system' => 'liqpay',
            'order_id' => $orderId
        ]);
        return $payment->LiqPay($orderId);
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

    /**
     * Gets common currency multiplier to convert to coins.
     * Needed for the client to convert currency to coins.
     */

    public function getCommonCurrencyMultiplier(Request $request)
    {
        $currency = $request->input('currency');
        return $this->paymentService->currencyToCoins(1, $currency);
    }

    private function getCapabilitiesFromAllDonates($userId): array
    {
        $allDonates = $this->userDataService->getAllDonates($userId);
        $totalDonates = $this->getTotalDonateInCoins($allDonates);
        return $this->userDataService->getCapabilitiesFromTotalDonate($totalDonates);
    }

    private function getTotalDonateInCoins($allDonates)
    {
        $totalDonate = 0;

        foreach ($allDonates as $donate) {
            $totalDonate += $this->paymentService->currencyToCoins($donate->amount, $donate->currency);
        }
        return $totalDonate;
    }

    public function getPrivilegesData(Request $request) {
        $server = $request->input('server');
        $serverId = $this->privilegesService->getServerIdByName($server);
        return Privilege::select('title', 'price')->where('server_id', $serverId)->get();
    }
}
