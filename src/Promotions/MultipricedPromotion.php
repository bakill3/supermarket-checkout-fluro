<?php
namespace SupermarketCheckout\Promotions;

use SupermarketCheckout\Product;
use SupermarketCheckout\PricingRule;

class MultipricedPromotion implements PricingRule {
    private $sku;
    private $requiredQuantity;
    private $specialPrice;

    public function __construct($sku, $requiredQuantity, $specialPrice) {
        $this->sku = $sku;
        $this->requiredQuantity = $requiredQuantity;
        $this->specialPrice = $specialPrice;
    }

    public function apply(array $items): array {
        $count = 0;
        $unitPrice = 0;
        foreach ($items as $item) {
            if ($item->sku == $this->sku) {
                $count++;
                $unitPrice = $item->unitPrice;
            }
        }

        $numSpecials = intdiv($count, $this->requiredQuantity);
        $remainder = $count % $this->requiredQuantity;

        $newItems = [];
        for ($i = 0; $i < $numSpecials; $i++) {
            $newItems[] = new Product($this->sku, $this->specialPrice);
        }

        for ($i = 0; $i < $remainder; $i++) {
            $newItems[] = new Product($this->sku, $unitPrice);
        }

        // Remove original items of this SKU and add the new items
        $items = array_filter($items, function($item) {
            return $item->sku != $this->sku;
        });

        return array_merge($items, $newItems);
    }
}