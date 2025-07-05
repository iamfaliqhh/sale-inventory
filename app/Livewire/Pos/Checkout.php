<?php

namespace App\Livewire\Pos;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Modules\Product\Entities\Product;

class Checkout extends Component
{

    public $listeners = ['productSelected', 'discountModalRefresh'];

    public $cart_instance;
    public $customers;
    public $sales_persons;
    public int $global_discount = 0;
    public int $shipping = 0;
    public int $wage = 0;
    public $quantity;
    public $check_quantity;
    public $discount_type;
    public $item_discount;
    public $data;
    public $customer_id;
    public $sales_person_id;
    public $total_amount;
    public $page;

    // Trade-in form properties
    public $trade_in_product_name = '';
    public $trade_in_product_code = '';
    public $trade_in_product_weight = '';
    public $trade_in_product_purity = '';
    public $trade_in_total_value = 0;

    protected $rules = [
        'trade_in_product_name' => 'required|string|max:255',
        'trade_in_product_code' => 'required|string|max:255|unique:products,product_code',
        'trade_in_product_weight' => 'required|numeric|min:0.01',
        'trade_in_product_purity' => 'required|numeric|min:1|max:100',
    ];

    public function mount($cartInstance, $customers, $salesPersons) {
        $this->cart_instance = $cartInstance;
        $this->customers = $customers;
        $this->sales_persons = $salesPersons;
        $this->global_discount = 0;
        $this->shipping = 0.00;
        $this->wage = 0.00;
        $this->check_quantity = [];
        $this->quantity = [];
        $this->discount_type = [];
        $this->item_discount = [];
        $this->page = request()->has('trade_in') ? 'Trade In' : 'Transaction';
        $this->total_amount = $this->calculateTotal();
    }

    public function hydrate() {
        $this->total_amount = $this->calculateTotal();
    }

    public function render() {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.pos.checkout', [
            'cart_items' => $cart_items
        ]);
    }

    public function proceed() {
        if ($this->customer_id != null) {
            $this->dispatch('showCheckoutModal');
        } else {
            session()->flash('message', 'Please Select Customer!');
        }
    }

    public function calculateTotal() {
        // Calculate total manually to handle negative trade-in values
        $cart = Cart::instance($this->cart_instance);
        $subtotal = 0;

        foreach ($cart->content() as $item) {
            $itemTotal = $item->price * $item->qty;
            $subtotal += $itemTotal;
        }

        $finalTotal = $subtotal + $this->shipping - $this->global_discount + $this->wage;

        return $finalTotal;
    }

    public function resetCart() {
        Cart::instance($this->cart_instance)->destroy();
    }

    public function productSelected($product) {
        $cart = Cart::instance($this->cart_instance);

        $exists = $cart->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product['id'];
        });

        if ($exists->isNotEmpty()) {
            session()->flash('message', 'Product exists in the cart!');

            return;
        }

        $cart->add([
            'id'      => $product['id'],
            'name'    => $product['product_name'],
            'qty'     => 1,
            'weight'  => $product['product_weight'],
            'price'   => $this->calculate($product)['price'],
            'options' => [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'sub_total'             => $this->calculate($product)['sub_total'],
                'code'                  => $product['product_code'],
                'stock'                 => $product['product_quantity'],
                'unit'                  => $product['product_unit'],
                'product_tax'           => $this->calculate($product)['product_tax'],
                'unit_price'            => $this->calculate($product)['unit_price'],
                'product_purity'        => $product['product_purity'],
            ]
        ]);

        $this->check_quantity[$product['id']] = $product['product_quantity'];
        $this->quantity[$product['id']] = 1;
        $this->discount_type[$product['id']] = 'fixed';
        $this->item_discount[$product['id']] = 0;
        $this->total_amount = $this->calculateTotal();
    }

    public function removeItem($row_id) {
        // Get the cart item before removing to check if it's a trade-in
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        // If it's a trade-in product, remove it from database too
        if (isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in) {
            $product = Product::find($cart_item->id);
            if ($product && $product->product_type === 'Trade In') {
                $product->delete();
            }

            // Remove from tracking arrays
            unset($this->check_quantity[$cart_item->id]);
            unset($this->quantity[$cart_item->id]);
        }

        // Remove from cart
        Cart::instance($this->cart_instance)->remove($row_id);

        // Recalculate total
        $this->total_amount = $this->calculateTotal();
    }

    public function updateQuantity($row_id, $product_id) {
        if ($this->check_quantity[$product_id] < $this->quantity[$product_id]) {
            session()->flash('message', 'The requested quantity is not available in stock.');

            return;
        }

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ]
        ]);

        $this->total_amount = $this->calculateTotal();
    }

    public function discountModalRefresh($product_id, $row_id) {
        $this->updateQuantity($row_id, $product_id);
    }

    public function setProductDiscount($row_id, $product_id) {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] == 'fixed') {
            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $this->item_discount[$product_id]
                ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] == 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * ($this->item_discount[$product_id] / 100);

            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $discount_amount
                ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }

        session()->flash('discount_message' . $product_id, 'Discount added to the product!');
    }

    public function calculate($product) {
        $price = 0;
        $unit_price = 0;
        $product_tax = 0;
        $sub_total = 0;

        if ($product['product_tax_type'] == 1) {
            $price = $product['product_price'] + ($product['product_price'] * ($product['product_order_tax'] / 100));
            $unit_price = $product['product_price'];
            $product_tax = $product['product_price'] * ($product['product_order_tax'] / 100);
            $sub_total = $product['product_price'] + ($product['product_price'] * ($product['product_order_tax'] / 100));
        } elseif ($product['product_tax_type'] == 2) {
            $price = $product['product_price'];
            $unit_price = $product['product_price'] - ($product['product_price'] * ($product['product_order_tax'] / 100));
            $product_tax = $product['product_price'] * ($product['product_order_tax'] / 100);
            $sub_total = $product['product_price'];
        } else {
            $price = $product['product_price'];
            $unit_price = $product['product_price'];
            $product_tax = 0.00;
            $sub_total = $product['product_price'];
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount) {
        Cart::instance($this->cart_instance)->update($row_id, ['options' => [
            'sub_total'             => $cart_item->price * $cart_item->qty,
            'code'                  => $cart_item->options->code,
            'stock'                 => $cart_item->options->stock,
            'unit'                 => $cart_item->options->unit,
            'product_tax'           => $cart_item->options->product_tax,
            'unit_price'            => $cart_item->options->unit_price,
            'product_discount'      => $discount_amount,
            'product_discount_type' => $this->discount_type[$product_id],
        ]]);


    }

    public function calculateTradeInValue() {
        if ($this->trade_in_product_weight) {
            $gold_price = current_gold_price('trade_in');
            $this->trade_in_total_value = $this->trade_in_product_weight * $gold_price;
        } else {
            $this->trade_in_total_value = 0;
        }
    }

    public function addTradeInProduct() {
        // Validate the trade-in product form
        $this->validate([
            'trade_in_product_name' => 'required|string|max:255',
            'trade_in_product_code' => 'required|string|max:255|unique:products,product_code',
            'trade_in_product_weight' => 'required|numeric|min:0.01',
            'trade_in_product_purity' => 'required|numeric|min:1|max:100',
        ]);

        // Calculate the trade-in value
        $this->calculateTradeInValue();

        // Find default category or create one
        $defaultCategory = \Modules\Product\Entities\Category::first();
        $categoryId = $defaultCategory ? $defaultCategory->id : 1;

        // Create the trade-in product in the database
        $tradeInProduct = Product::create([
            'product_name' => $this->trade_in_product_name,
            'product_code' => $this->trade_in_product_code,
            'product_weight' => $this->trade_in_product_weight,
            'product_purity' => $this->trade_in_product_purity,
            'product_price' => $this->trade_in_total_value,
            'product_cost' => $this->trade_in_total_value,
            'product_quantity' => 1,
            'product_type' => 'Trade In',
            'product_unit' => 'gram',
            'product_stock_alert' => 0,
            'product_order_tax' => 0,
            'product_tax_type' => 3,
            'category_id' => $categoryId,
        ]);

        // Add to cart with negative value for trade-in
        $cart = Cart::instance($this->cart_instance);
        $cart->add([
            'id'      => $tradeInProduct->id,
            'name'    => $tradeInProduct->product_name,
            'qty'     => 1,
            'weight'  => $tradeInProduct->product_weight,
            'price'   => -$this->trade_in_total_value, // Negative value for trade-in
            'options' => [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'sub_total'             => -$this->trade_in_total_value,
                'code'                  => $tradeInProduct->product_code,
                'stock'                 => 1,
                'unit'                  => $tradeInProduct->product_unit,
                'product_tax'           => 0,
                'unit_price'            => -$this->trade_in_total_value,
                'product_purity'        => $tradeInProduct->product_purity,
                'is_trade_in'           => true,
            ]
        ]);

        // Reset the form
        $this->resetTradeInForm();

        // Update total amount
        $this->check_quantity[$tradeInProduct->id] = 1;
        $this->quantity[$tradeInProduct->id] = 1;
        $this->total_amount = $this->calculateTotal();

        session()->flash('message', 'Trade-in product added successfully!');
    }

    public function resetTradeInForm() {
        $this->trade_in_product_name = '';
        $this->trade_in_product_code = '';
        $this->trade_in_product_weight = '';
        $this->trade_in_product_purity = '';
        $this->trade_in_total_value = 0;
    }
}
