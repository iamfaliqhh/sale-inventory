<?php
namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\GoldPrice;

class GoldPriceController extends Controller
{
    public function index()
    {
        $prices = GoldPrice::orderByDesc('created_at')->orderByDesc('id')->get();
        return view('setting::gold_price.index', compact('prices'));
    }

    public function create()
    {
        return view('setting::gold_price.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_price' => 'required|numeric|min:0',
            'trade_in_price'    => 'required|numeric|min:0',
            'buyback_price'     => 'required|numeric|min:0',
        ]);
        
        GoldPrice::create($data);
        toast('Gold Price Saved!', 'success');
        return redirect()->route('gold-price.index');
    }
}
