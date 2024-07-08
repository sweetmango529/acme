# Basket Implementation

## Overview

This PHP implementation provides a simple basket system that can be initialized with a product catalogue, delivery charge rules, and offers. It allows adding products to the basket and calculating the total cost, taking into account the delivery and offer rules.

## Usage

### Initialization

The `Basket` class is initialized with three parameters:
- `$catalogue`: An associative array where keys are product codes and values are their prices.
- `$deliveryRules`: An array of `DeliveryRule` objects.
- `$offers`: An array of `Offer` objects.

### Methods

- `add($productCode)`: Adds a product to the basket using its product code.
- `total()`: Returns the total cost of the basket, considering the delivery and offer rules.

### Example
