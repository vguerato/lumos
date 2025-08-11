<?php

namespace App\Providers;

use App\Listeners\Auth\UserCacheSubscriber;
use App\Models\Account;
use App\Models\Transaction;
use App\Observers\AccountObserver;
use App\Policies\AccountPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Listeners
         */
        Event::subscribe(UserCacheSubscriber::class);

        /*
         * Observers
         */
        Account::observe(AccountObserver::class);

        /*
         * Policies
         */
        Gate::policy(Account::class, AccountPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
    }
}
