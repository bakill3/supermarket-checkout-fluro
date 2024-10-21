<?php
require_once __DIR__ . '/vendor/autoload.php';

use SupermarketCheckout\Product;
use SupermarketCheckout\Checkout;
use SupermarketCheckout\Promotions\MultipricedPromotion;
use SupermarketCheckout\Promotions\BuyNGetOneFreePromotion;
use SupermarketCheckout\Promotions\MealDealPromotion;

$products = [
    new Product('A', 50),
    new Product('B', 75),
    new Product('C', 25),
    new Product('D', 150),
    new Product('E', 200),
];

$pricingRules = [
    new MultipricedPromotion('B', 2, 125), // 2 for £1.25
    new BuyNGetOneFreePromotion('C', 3, 1), // Buy 3, get 1 free
    new MealDealPromotion(['D', 'E'], 300), // D and E for £3
];

function runScenario($scenarioNumber, $itemsToScan, $pricingRules, $products) {
    $checkout = new Checkout($pricingRules, $products);

    foreach ($itemsToScan as $sku) {
        $checkout->scan($sku);
    }

    $total = $checkout->total();

    return [
        'Scenario' => $scenarioNumber,
        'Items Scanned' => implode(', ', $itemsToScan),
        'Total Price' => '£' . number_format($total / 100, 2),
    ];
}

$scenarios = [
    [
        'number' => '1',
        'items' => ['B', 'A', 'B'],
    ],
    [
        'number' => '2',
        'items' => ['C', 'C', 'C', 'C'],
    ],
    [
        'number' => '3',
        'items' => ['D', 'E'],
    ],
    [
        'number' => '4',
        'items' => ['A', 'B', 'C', 'D', 'E', 'C', 'C', 'B'],
    ],
    [
        'number' => '5',
        'items' => ['A', 'A', 'A', 'B', 'B', 'B', 'C', 'C', 'C', 'C', 'D', 'E', 'D'],
    ],
];

$tableData = [];
foreach ($scenarios as $scenario) {
    $result = runScenario($scenario['number'], $scenario['items'], $pricingRules, $products);
    $tableData[] = $result;
}

// Generate HTML output
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supermarket Checkout Scenarios</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #1f6f8b;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        h1 {
            text-align: center;
            color: #1f6f8b;
        }
    </style>
</head>
<body>
    <h1>Supermarket Checkout Scenarios</h1>
    <table>
        <tr>
            <th>Scenario</th>
            <th>Items Scanned</th>
            <th>Total Price</th>
        </tr>
        <?php foreach ($tableData as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Scenario']); ?></td>
            <td><?php echo htmlspecialchars($row['Items Scanned']); ?></td>
            <td><?php echo htmlspecialchars($row['Total Price']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
