<?php
namespace SupermarketCheckout;

use SupermarketCheckout\Product;
use SupermarketCheckout\PricingRule;

class Checkout {
    private $pricingRules;
    private $items = [];
    private $products = [];

    public function __construct(array $pricingRules, array $products) {
        $this->pricingRules = $pricingRules;
        foreach ($products as $product) {
            $this->products[$product->sku] = $product;
        }
    }

    public function scan($sku) {
        if (!isset($this->products[$sku])) {
            throw new Exception("Product with SKU {$sku} not found.");
        }
        $this->items[] = clone $this->products[$sku];
    }

    public function total() {
        $items = $this->items;
        foreach ($this->pricingRules as $rule) {
            $items = $rule->apply($items);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item->unitPrice;
        }

        return $total;
    }
}