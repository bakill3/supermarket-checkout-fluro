<?php
namespace SupermarketCheckout;

interface PricingRule {
    public function apply(array $items): array;
}