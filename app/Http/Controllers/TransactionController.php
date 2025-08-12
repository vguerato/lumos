<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\Transaction\TransactService;
use App\Services\Transaction\TransferService;
use App\Services\TransactionService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TransactionController extends Controller
{
    /**
     * POST /api/transact
     * @param Request $request
     * @param TransactService $transactService
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function transact(
        Request $request,
        TransactService $transactService
    ): JsonResponse
    {
        $data = $request->validate([
            'account_id' => ['required','string'],
            'amount' => ['required','numeric','gt:0'],
            'type' => ['required','string','in:deposit,withdrawal'],
            'description' => ['nullable','string','max:255'],
        ]);

        $this->authorize('create', [Transaction::class, $data['account_id']]);

        $transaction = $transactService->execute($data);

        return response()->json($transaction, 201);
    }

    /**
     * POST /api/transfer
     * @param Request $request
     * @param TransferService $transferService
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function transfer(
        Request $request,
        TransferService $transferService
    ): JsonResponse
    {
        $data = $request->validate([
            'account_from' => ['required','string'],
            'account_to' => ['required','string'],
            'amount' => ['required','numeric','gt:0'],
            'description' => ['nullable','string','max:255'],
        ]);

        $this->authorize('transfer', [Transaction::class, $data['account_from']]);

        $transaction = $transferService->execute($data);

        return response()->json($transaction, 201);
    }
}
