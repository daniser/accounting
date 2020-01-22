<?php

namespace Daniser\Accounting;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

//use Money\Currencies\ISOCurrencies;
//use Money\Formatter\DecimalMoneyFormatter;
//use Money\Parser\DecimalMoneyParser;

class AccountingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/accounting.php' => $this->app->configPath('accounting.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'migrations');

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->commands([
                Console\LedgerTransferCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/accounting.php', 'accounting');

        $this->registerAccountingManager();

        $this->registerAccountingDriver();

        $this->registerAccountOwnerResolver();

        /*$this->app->singleton(Contracts\Ledger::class, function () {
            $currencies = new ISOCurrencies;

            return $this->app->make(Ledger::class, [
                'config' => $this->app['config']['accounting'],
                'parser' => new DecimalMoneyParser($currencies),
                'formatter' => new DecimalMoneyFormatter($currencies),
            ]);
        });

        $this->app->alias(Contracts\Ledger::class, 'ledger');*/
    }

    /**
     * Register the accounting manager instance.
     *
     * @return void
     */
    protected function registerAccountingManager()
    {
        $this->app->singleton('accounting', function ($app) {
            return new AccountingManager($app);
        });
    }

    /**
     * Register the accounting driver instance.
     *
     * @return void
     */
    protected function registerAccountingDriver()
    {
        $this->app->singleton(Contracts\Ledger::class, function () {
            return $this->app->make('accounting')->driver();
        });

        $this->app->alias(Contracts\Ledger::class, 'ledger');
    }

    /**
     * Register the account owner resolver instance.
     *
     * @return void
     */
    protected function registerAccountOwnerResolver()
    {
        $this->app->singleton(Contracts\AccountOwnerResolver::class, Support\AccountOwnerResolver::class);
    }

    public function provides()
    {
        return ['accounting', Contracts\Ledger::class, 'ledger', Contracts\AccountOwnerResolver::class];
    }
}
