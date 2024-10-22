# Supermarket Checkout

This is a PHP implementation of a supermarket checkout system that calculates the total price of scanned items, applying special promotions. The system is designed to accept items in any order and applies various types of promotions as per the current pricing rules.

## Features
- **Scan Items in Any Order**: The checkout accepts items in any order, recognizing applicable promotions regardless of the scanning sequence.
- **Apply Various Promotions**:
    - **Multipriced Promotions**: Buy a certain quantity for a special price (e.g., 2 for £1.25).
    - **Buy N Get One Free**: Buy N items and get one free (e.g., Buy 3, get 1 free).
    - **Meal Deals**: Buy different items together for a special price (e.g., Buy D and E for £3).
- **Flexible Pricing Rules**: Easily update pricing rules to accommodate frequent price changes.
- **Comprehensive Unit Tests**: Includes PHPUnit tests to ensure correctness and prevent regressions.

## Requirements
- PHP 7.3 or Higher
- Composer

## Installation

1. Clone the repository
```
git clone https://github.com/bakill3/supermarket-checkout-fluro.git
cd supermarket-checkout
```
2. Install Dependencies
```
composer install
```

## Running Tests

1. Run Tests via Composer
```
composer test
```
2. Alternative Way to Run Tests
```
vendor/bin/phpunit
```

## Business Domain
### Products
- SKU: Stock Keeping Unit, a unique identifier for each product (e.g., 'A', 'B', 'C').
- Unit Price: Price per item in pence.
### Promotions
1. Multipriced Promotions: Buy a certain quantity of the same item for a special price.
    - Example: Buy 2 B's for £1.25 instead of £0.75 each.
2. Buy N Get One Free: Buy N items and get one additional item for free.
    - Example: Buy 3 C's, get one free.
3. Meal Deals: Buy different items together for a special price.
    - Example: Buy D and E together for £3.00.

## Output of Index.php
![Supermarket Checkout Scenarios](https://github.com/bakill3/supermarket-checkout-fluro/blob/master/output.png)



