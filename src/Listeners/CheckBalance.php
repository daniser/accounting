<?php

namespace Daniser\Accounting\Listeners;

use Daniser\Accounting\Contracts\Ledger;
use Daniser\Accounting\Events\TransactionCommitting;

class CheckBalance
{
    /** @var Ledger $ledger */
    protected Ledger $ledger;

    /**
     * Create the event listener.
     *
     * @param Ledger $ledger
     */
    public function __construct(Ledger $ledger)
    {
        $this->ledger = $ledger;
    }

    /**
     * Handle the event.
     *
     * @param TransactionCommitting $event
     *
     * @return bool
     */
    public function handle(TransactionCommitting $event)
    {
        $balance = $event->transaction->getSource()->getBalance();
        $amount = $this->ledger->convertMoney($event->transaction->getAmount(), $balance->getCurrency());

        if ($insufficientFunds = $balance->lessThan($amount)) {
            report(new \RuntimeException('Transaction canceled: insufficient funds.'));
        }

        return $insufficientFunds ? false : null;
    }
}
