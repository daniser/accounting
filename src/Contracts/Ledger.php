<?php

namespace Daniser\Accounting\Contracts;

use Money\Money;
use Money\Currency;
use Daniser\Accounting\Exceptions\AccountNotFoundException;
use Daniser\Accounting\Exceptions\TransactionNotFoundException;

interface Ledger
{
    /**
     * @param Money $money
     * @param Currency $counterCurrency
     * @param int|null $roundingMode
     *
     * @return Money
     */
    public function convertMoney(Money $money, Currency $counterCurrency, $roundingMode = null): Money;

    /**
     * @param AccountOwner $owner
     * @param string|null $type
     * @param Currency|null $currency
     *
     * @throws AccountNotFoundException
     *
     * @return Account
     */
    public function getAccount(AccountOwner $owner, $type = null, Currency $currency = null): Account;

    /**
     * @param mixed $id
     *
     * @throws TransactionNotFoundException
     *
     * @return Transaction
     */
    public function getTransaction($id): Transaction;

    /**
     * @param Account $source
     * @param Account $destination
     * @param Money $amount
     * @param array|null $payload
     *
     * @return Transaction
     */
    public function newTransaction(Account $source, Account $destination, Money/*|int*/ $amount, array $payload = null): Transaction;

    /**
     * @return int
     */
    public function getRoundingMode(): int;

    /**
     * @param int $type
     */
    public function setRoundingMode(int $type): void;

    /**
     * @return string
     */
    public function getDefaultType(): string;

    /**
     * @param string $type
     */
    public function setDefaultType(string $type): void;

    /**
     * @return Currency
     */
    public function getDefaultCurrency(): Currency;

    /**
     * @param Currency $currency
     */
    public function setDefaultCurrency(Currency $currency): void;

    /**
     * @return bool
     */
    public function getAutoCommit(): bool;

    /**
     * @param bool $autoCommit
     */
    public function setAutoCommit(bool $autoCommit): void;
	
	/**
	 * @return int
	 */
	public function getCommitAttempts(): int;
	
	/**
	 * @param int $attempts
	 */
	public function setCommitAttempts(int $attempts): void;
}
