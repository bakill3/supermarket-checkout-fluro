<?php
namespace SupermarketCheckout\Promotions;

use SupermarketCheckout\Product;
use SupermarketCheckout\PricingRule;

class BuyNGetOneFreePromotion implements PricingRule {
    private $sku;
    private $buyQuantity;
    private $freeQuantity;

    public function __construct($sku, $buyQuantity, $freeQuantity = 1) {
        $this->sku = $sku;
        $this->buyQuantity = $buyQuantity;
        $this->freeQuantity = $freeQuantity;
    }

    public function apply(array $items): array {
        $skuItems = array_filter($items, function($item) {
            return $item->sku == $this->sku;
        });

        $count = count($skuItems);
        $groupSize = $this->buyQuantity + $this->freeQuantity;
        $numGroups = intdiv($count, $groupSize);
        $remainingItems = $count % $groupSize;

        $payableItems = $numGroups * $this->buyQuantity + min($remainingItems, $this->buyQuantity);

        $newItems = [];
        $unitPrice = 0;
        foreach ($items as $item) {
            if ($item->sku != $this->sku) {
                $newItems[] = $item;
            } else {
                $unitPrice = $item->unitPrice;
            }
        }

        for ($i = 0; $i < $payableItems; $i++) {
            $newItems[] = new Product($this->sku, $unitPrice);
        }

        return $newItems;
    }
}