<?php
namespace SupermarketCheckout\Promotions;

use SupermarketCheckout\Product;
use SupermarketCheckout\PricingRule;

class MealDealPromotion implements PricingRule {
    private $skus;
    private $specialPrice;

    public function __construct(array $skus, $specialPrice) {
        $this->skus = $skus;
        $this->specialPrice = $specialPrice;
    }

    public function apply(array $items): array {
        // Count the number of times we can apply the meal deal
        $skuCounts = [];
        foreach ($this->skus as $sku) {
            $skuCounts[$sku] = 0;
        }
        foreach ($items as $item) {
            if (in_array($item->sku, $this->skus)) {
                $skuCounts[$item->sku]++;
            }
        }

        $numDeals = min($skuCounts);

        if ($numDeals == 0) {
            return $items;
        }

        // Remove items included in the meal deal
        $itemsCopy = $items;
        foreach ($this->skus as $sku) {
            $itemsCopy = $this->removeItems($itemsCopy, $sku, $numDeals);
        }

        // Add meal deals
        for ($i = 0; $i < $numDeals; $i++) {
            $itemsCopy[] = new Product('MealDeal', $this->specialPrice);
        }

        return $itemsCopy;
    }

    private function removeItems($items, $sku, $quantity) {
        $count = 0;
        $newItems = [];
        foreach ($items as $item) {
            if ($item->sku == $sku && $count < $quantity) {
                $count++;
                // Skip item (it's part of meal deal)
            } else {
                $newItems[] = $item;
            }
        }
        return $newItems;
    }
}