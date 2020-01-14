<?php

namespace Daniser\Accounting\Contracts;

use Money\Money;
use Daniser\Accounting\Exceptions\TransactionException;

interface Transaction
{
    const STATUS_STARTED = 0;
    const STATUS_COMMITTED = 1;
    const STATUS_CANCELED = 2;
    const STATUS_FAILED = 3;

    public function getSource(): Account;

    public function getDestination(): Account;

    public function getAmount(): Money;

    public function getPayload() : ?array;

    public function getStatus(): int;

    /**
     * @throws TransactionException
     *
     * @return $this
     */
    public function commit(): self;

    /**
     * @return $this
     */
    public function cancel(): self;

    /**
     * @return static|$this
     */
    public function revert(): self;

    public function rollback();
}
