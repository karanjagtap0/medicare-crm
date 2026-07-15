<?php

namespace App\Interfaces\Inventory;

interface InventoryRepositoryInterface
{
    public function getDashboardStats();
    public function getCurrentStock(array $filters);
    public function getStockLedger(array $filters);
    public function getInventoryTransactions(array $filters);
    public function createStockAdjustment(array $data);
    public function createStockTransfer(array $data);
    public function createInventoryAudit(array $data);
    public function getInventoryValuation(array $filters);
}
