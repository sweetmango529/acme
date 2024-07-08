<?php

class Basket {
    private $catalogue;
    private $deliveryRules;
    private $offers;
    private $items = [];

    public function __construct($catalogue, $deliveryRules, $offers) {
        $this->catalogue = $catalogue;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }

    public function add($productCode) {
        if (isset($this->catalogue[$productCode])) {
            $this->items[] = $productCode;
        } else {
            throw new Exception("Product code $productCode not found in catalogue.");
        }
    }

    public function total() {
        $total = 0;
        $itemCounts = array_count_values($this->items);

        // Apply offers
        foreach ($this->offers as $offer) {
            $total += $offer->apply($itemCounts, $this->catalogue);
        }

        // Calculate remaining items
        foreach ($itemCounts as $code => $count) {
            $total += $this->catalogue[$code] * $count;
        }

        // Apply delivery charges
        foreach ($this->deliveryRules as $rule) {
            $total += $rule->apply($total);
        }

        return number_format($total, 2);
    }
}

class Offer {
    private $productCode;
    private $discount;

    public function __construct($productCode, $discount) {
        $this->productCode = $productCode;
        $this->discount = $discount;
    }

    public function apply(&$itemCounts, $catalogue) {
        $total = 0;
        if (isset($itemCounts[$this->productCode])) {
            $count = $itemCounts[$this->productCode];
            $total += $count * $catalogue[$this->productCode] * (1 - $this->discount);
            unset($itemCounts[$this->productCode]);
        }
        return $total;
    }
}

class DeliveryRule {
    private $threshold;
    private $charge;

    public function __construct($threshold, $charge) {
        $this->threshold = $threshold;
        $this->charge = $charge;
    }

    public function apply($total) {
        return $total < $this->threshold ? $this->charge : 0;
    }
}

// Example usage
$catalogue = [
    'B01' => 24.95,
    'R01' => 32.95,
    'G01' => 3.95
];

$deliveryRules = [
    new DeliveryRule(50, 4.95),
    new DeliveryRule(90, 2.95)
];

$offers = [
    new Offer('R01', 0.5) // 50% off on R01
];

$basket = new Basket($catalogue, $deliveryRules, $offers);
$basket->add('B01');
$basket->add('G01');
echo $basket->total(); // Output: 37.85