<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\Account\CreateAccountsService;
use App\Services\Account\DeleteAccountsService;
use App\Services\Account\GetAccountDetailService;
use App\Services\Account\GetAccountsService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * GET /api/accounts
     * @param GetAccountsService $getAccountsService
     * @return JsonResponse
     */
    public function index(GetAccountsService $getAccountsService): JsonResponse
    {
        $accounts = $getAccountsService->execute();
        return response()->json($accounts);
    }

    /**
     * GET /api/accounts/{accountId}
     * @param string $accountId
     * @param Request $request
     * @param GetAccountDetailService $getAccountsService
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function details(
        string $accountId,
        Request $request,
        GetAccountDetailService $getAccountsService
    ): JsonResponse
    {
        $this->authorize('view', [Account::class, $accountId]);

        $account = $getAccountsService->execute($accountId, $request->has('complete'));

        return response()->json($account);
    }

    /**
     * POST /api/accounts
     * @param Request $request
     * @param CreateAccountsService $createAccountsService
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request, CreateAccountsService $createAccountsService): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'balance' => ['numeric'],
        ]);

        $account = $createAccountsService->execute($data);

        return response()->json($account, 201);
    }

    /**
     * DELETE /api/accounts/{accountId}
     * @param string $accountId
     * @param DeleteAccountsService $deleteAccountsService
     * @return JsonResponse
     */
    public function delete(string $accountId, DeleteAccountsService $deleteAccountsService): JsonResponse
    {
        $deleteAccountsService->execute($accountId);
        return response()->json(true);
    }
}
