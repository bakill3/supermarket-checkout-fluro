<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use SupermarketCheckout\Product;
use SupermarketCheckout\Checkout;
use SupermarketCheckout\Promotions\MultipricedPromotion;
use SupermarketCheckout\Promotions\BuyNGetOneFreePromotion;
use SupermarketCheckout\Promotions\MealDealPromotion;

class CheckoutTest extends TestCase
{
    private $products;
    private $pricingRules;

    protected function setUp(): void
    {
        $this->products = [
            new Product('A', 50),
            new Product('B', 75),
            new Product('C', 25),
            new Product('D', 150),
            new Product('E', 200),
        ];

        $this->pricingRules = [
            new MultipricedPromotion('B', 2, 125), // 2 for £1.25
            new BuyNGetOneFreePromotion('C', 3, 1), // Buy 3, get 1 free
            new MealDealPromotion(['D', 'E'], 300), // D and E for £3
        ];
    }

    public function testSingleItem()
    {
        $checkout = new Checkout($this->pricingRules, $this->products);
        $checkout->scan('A');
        $this->assertEquals(50, $checkout->total());
    }

    public function testMultipricedPromotion()
    {
        $checkout = new Checkout($this->pricingRules, $this->products);
        $checkout->scan('B');
        $checkout->scan('B');
        $this->assertEquals(125, $checkout->total());
    }

    public function testBuyNGetOneFreePromotion()
    {
        $checkout = new Checkout($this->pricingRules, $this->products);
        $checkout->scan('C');
        $checkout->scan('C');
        $checkout->scan('C');
        $checkout->scan('C');
        $this->assertEquals(75, $checkout->total()); // Pay for 3, get 1 free
    }

    public function testMealDealPromotion()
    {
        $checkout = new Checkout($this->pricingRules, $this->products);
        $checkout->scan('D');
        $checkout->scan('E');
        $this->assertEquals(300, $checkout->total());
    }

    public function testBugWithItemB()
    {
        $checkout = new Checkout($this->pricingRules, $this->products);
        $checkout->scan('B');
        $this->assertEquals(75, $checkout->total()); // Should be 75p, not double-counted
    }

}
