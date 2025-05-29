<?php
namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\GoldPrice;

class GoldPriceController extends Controller
{
    public function index()
    {
        $prices = GoldPrice::orderByDesc('date')->orderByDesc('id')->get();
        return view('setting::gold_price.index', compact('prices'));
    }

    public function create()
    {
        return view('setting::gold_price.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'type'  => 'required|in:sale,buyback',
            'price' => 'required|numeric|min:0',
            'note'  => 'nullable|string|max:255',
        ]);
        GoldPrice::create($request->only('date', 'type', 'price', 'note'));
        toast('Gold Price Saved!', 'success');
        return redirect()->route('gold-price.index');
    }
}
