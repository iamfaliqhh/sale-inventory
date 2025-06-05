<?php

namespace Modules\People\Http\Controllers;

use Modules\People\DataTables\SalesPersonDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\SalesPerson;

class SalesPersonController extends Controller
{

    public function index(SalesPersonDataTable $dataTable) {
        abort_if(Gate::denies('access_sales_person'), 403);

        return $dataTable->render('people::sales_person.index');
    }


    public function create() {
        abort_if(Gate::denies('create_sales_person'), 403);

        return view('people::sales_person.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_sales_person'), 403);

        $request->validate([
            'sales_person_name'  => 'required|string|max:255',
            'sales_person_phone' => 'required|max:255',
            'sales_person_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address'        => 'required|string|max:500',
        ]);

        SalesPerson::create([
            'sales_person_name'  => $request->sales_person_name,
            'sales_person_phone' => $request->sales_person_phone,
            'sales_person_email' => $request->sales_person_email,
            'city'           => $request->city,
            'country'        => $request->country,
            'address'        => $request->address
        ]);

        toast('Sales Person Created!', 'success');

        return redirect()->route('sales_person.index');
    }


    public function show(SalesPerson $sales_person) {
        abort_if(Gate::denies('show_sales_person'), 403);

        return view('people::sales_person.show', compact('sales_person'));
    }


    public function edit(SalesPerson $sales_person) {
        abort_if(Gate::denies('edit_sales_person'), 403);

        return view('people::sales_person.edit', compact('sales_person'));
    }


    public function update(Request $request, SalesPerson $sales_person) {
        abort_if(Gate::denies('update_sales_person'), 403);

        $request->validate([
            'sales_person_name'  => 'required|string|max:255',
            'sales_person_phone' => 'required|max:255',
            'sales_person_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address'        => 'required|string|max:500',
        ]);

        $sales_person->update([
            'sales_person_name'  => $request->sales_person_name,
            'sales_person_phone' => $request->sales_person_phone,
            'sales_person_email' => $request->sales_person_email,
            'city'           => $request->city,
            'country'        => $request->country,
            'address'        => $request->address
        ]);

        toast('SalesP erson Updated!', 'info');

        return redirect()->route('sales_person.index');
    }


    public function destroy(SalesPerson $sales_person) {
        abort_if(Gate::denies('delete_sales_person'), 403);

        $sales_person->delete();

        toast('Sales Person Deleted!', 'warning');

        return redirect()->route('sales_person.index');
    }
}
