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
        $this->product_type = request()->has('tradein') ? 'Buy Back' : 'Normal';
    }

    public function render() {
        return view('livewire.pos.product-list', [
            'products' => Product::when($this->product_type, function ($query) {
                return $query->where('product_type', $this->product_type);
            })
            ->when($this->category_id, function ($query) {
                return $query->where('category_id', $this->category_id);
            })
            ->paginate($this->limit)
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
