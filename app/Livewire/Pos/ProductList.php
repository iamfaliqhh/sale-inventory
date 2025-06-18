<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Product\Entities\Product;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'selectedCategory' => 'categoryChanged',
        'showCount'        => 'showCountChanged'
    ];

    public $categories;
    public $category_id;
    public $limit = 9;
    public $product_type;

    public function mount($categories) {
        $this->categories = $categories;
        $this->category_id = '';
        $this->product_type = request()->has('trade_in') ? 'Trade In' : 'Normal';
    }

    public function render() {
        $gold_price = $this->product_type === 'Trade In' ? current_gold_price('trade_in') : current_gold_price('transaction');
        return view('livewire.pos.product-list', [
            'products' => Product::when($this->product_type, function ($query) {
                return $query->where('product_type', $this->product_type);
            })
            ->when($this->category_id, function ($query) {
                return $query->where('category_id', $this->category_id);
            })
            ->paginate($this->limit)
            ->through(function ($product) use ($gold_price) {
                $product->product_price = $product->product_weight * $gold_price;
                return $product;
            }),
            'gold_price' => $gold_price
        ]);
    }

    public function categoryChanged($category_id) {
        $this->category_id = $category_id;
        $this->resetPage();
    }

    public function showCountChanged($value) {
        $this->limit = $value;
        $this->resetPage();
    }

    public function selectProduct($product) {
        $this->dispatch('productSelected', $product);
    }
}
