<?php

namespace Modules\Product\Http\Controllers;

use Modules\Product\DataTables\ProductDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Upload\Entities\Upload;

class ProductController extends Controller
{

    public function index(ProductDataTable $dataTable) {
        abort_if(Gate::denies('access_products'), 403);

        return $dataTable
        ->with('type', "Normal")
        ->render('product::products.index');
    }

    public function buyback(ProductDataTable $dataTable) {
        abort_if(Gate::denies('access_products'), 403);

        return $dataTable
        ->with('type', "Buy Back")
        ->render('product::products.index');
    }


    public function create() {
        abort_if(Gate::denies('create_products'), 403);

        $type = "Normal";
        $gold_price = current_gold_price('transaction');
        if(request()->has('buyback')) {
            $type = "Buy Back";
            $gold_price = current_gold_price('buyback');
        }
        
        return view('product::products.create', compact('type', 'gold_price'));
    }


    public function store(StoreProductRequest $request) {
        $view = 'products.index';
        $message = 'Product Created!';

        try {
            DB::beginTransaction();
            $product = Product::create($request->except('document'));

            if($product && $request->product_type == "Buy Back") {
                $this->createBuyBackSales($product);
                $view = 'sales.index';
                $message = 'Buy Back Product Created!';
            }

            if ($request->has('document')) {
                foreach ($request->input('document', []) as $file) {
                    $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            toast('Error: ' . $e->getMessage(), 'error');

            return redirect()->back()->withInput();
        }

        toast($message, 'success');
        return redirect()->route($view);
    }


    private function createBuyBackSales(Product $product) {
        $sale = Sale::create([
            'date' => now(),
            'customer_id' => $product->customer_id,
            'customer_name' => Customer::findOrFail($product->customer_id)->customer_name,
            'tax_percentage' => 0,
            'discount_percentage' => 0,
            'shipping_amount' => 0 * 100,
            'paid_amount' => $product->product_weight * current_gold_price('buyback') * 100,
            'total_amount' => $product->product_weight * current_gold_price('buyback') * 100,
            'due_amount' => 0 * 100,
            'status' => 'Completed',
            'type' => 'Buy Back',
            'payment_status' => 'Paid',
            'payment_method' => 'Cash',
            'note' => $product->product_note ?? '',
        ]);

        SaleDetails::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'quantity' => 1, // Assuming one product per buyback sale
            'price' => $product->product_weight * current_gold_price('buyback') * 100,
            'unit_price' => $product->product_weight * current_gold_price('buyback') * 100,
            'sub_total' => $product->product_weight * current_gold_price('buyback') * 100,
            'product_discount_amount' => 0 * 100,
            'product_discount_type' => 'fixed',
            'product_tax_amount' => 0 * 100,
        ]);
        SalePayment::create([
            'date' => now(),
            'reference' => 'BUYBACK/' . $product->product_code,
            'amount' => $product->product_weight * current_gold_price('buyback'),
            'sale_id' => $sale->id,
            'payment_method' => 'Cash',
        ]);
    }


    public function show(Product $product) {
        abort_if(Gate::denies('show_products'), 403);

        return view('product::products.show', compact('product'));
    }


    public function edit(Product $product) {
        abort_if(Gate::denies('edit_products'), 403);

        $gold_price = current_gold_price('transaction');
        if($product->product_type == "Buy Back") {
            $gold_price = current_gold_price('buyback');
        }

        return view('product::products.edit', compact('product', 'gold_price'));
    }


    public function update(UpdateProductRequest $request, Product $product) {
        $product->update($request->except('document'));

        if ($request->has('document')) {
            if (count($product->getMedia('images')) > 0) {
                foreach ($product->getMedia('images') as $media) {
                    if (!in_array($media->file_name, $request->input('document', []))) {
                        $media->delete();
                    }
                }
            }

            $media = $product->getMedia('images')->pluck('file_name')->toArray();

            foreach ($request->input('document', []) as $file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
                }
            }
        }

        toast('Product Updated!', 'info');

        return redirect()->route('products.index');
    }


    public function destroy(Product $product) {
        abort_if(Gate::denies('delete_products'), 403);

        $product->delete();

        toast('Product Deleted!', 'warning');

        return redirect()->route('products.index');
    }
}
