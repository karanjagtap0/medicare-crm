<?php

namespace App\Services\Inventory;

use App\Interfaces\Inventory\InventoryRepositoryInterface;

class InventoryService
{
    public function __construct(private InventoryRepositoryInterface $inventoryRepository)
    {
    }

    public function getDashboardStats()
    {
        return $this->inventoryRepository->getDashboardStats();
    }

    public function getCurrentStock(array $filters)
    {
        return $this->inventoryRepository->getCurrentStock($filters);
    }

    public function getStockLedger(array $filters)
    {
        return $this->inventoryRepository->getStockLedger($filters);
    }

    public function getInventoryTransactions(array $filters)
    {
        return $this->inventoryRepository->getInventoryTransactions($filters);
    }

    public function createStockAdjustment(array $data)
    {
        return $this->inventoryRepository->createStockAdjustment($data);
    }

    public function createStockTransfer(array $data)
    {
        return $this->inventoryRepository->createStockTransfer($data);
    }

    public function createInventoryAudit(array $data)
    {
        return $this->inventoryRepository->createInventoryAudit($data);
    }

    public function getInventoryValuation(array $filters)
    {
        return $this->inventoryRepository->getInventoryValuation($filters);
    }
}
