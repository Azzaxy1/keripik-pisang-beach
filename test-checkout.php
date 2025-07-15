<?php

// Simple test file to verify our setup
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Cart;
use App\Models\Product;

// Test if models can be instantiated
echo "Testing models...\n";

$user = new User();
echo "User model: OK\n";

$order = new Order();
echo "Order model: OK\n";

$address = new UserAddress();
echo "UserAddress model: OK\n";

$cart = new Cart();
echo "Cart model: OK\n";

echo "\nAll models can be instantiated successfully!\n";
