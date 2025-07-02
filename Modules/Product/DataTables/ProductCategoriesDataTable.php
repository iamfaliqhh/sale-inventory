<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Category;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductCategoriesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('search') && $search = request('search')['value']) {
                    $search = strtolower($search);
                    $query->where(function ($q) use ($search) {
                        $q->whereRaw('LOWER(category_code) LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('LOWER(category_name) LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('LOWER(created_at) LIKE ?', ["%{$search}%"]);
                        // Only filter by products_count if search is numeric
                        if (is_numeric($search)) {
                            $q->orHaving('products_count', '=', (int)$search);
                        }
                    });
                }
            })
            ->editColumn('products_sum_product_quantity', function ($row) {
                return $row->products_sum_product_quantity ?? 0;
            })
            ->addColumn('action', function ($data) {
                return view('product::categories.partials.actions', compact('data'));
            });
    }

    public function query(Category $model) {
        return $model->newQuery()->withSum('products', 'product_quantity');
    }

    public function html() {
        return $this->builder()
            ->setTableId('product_categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(4)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Print'),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Reset'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Reload')
            );
    }

    protected function getColumns() {
        return [
            Column::make('category_code')
                ->addClass('text-center'),

            Column::make('category_name')
                ->addClass('text-center'),

            Column::make('products_sum_product_quantity')
                ->title('Products Count')
                ->addClass('text-center'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'ProductCategories_' . date('YmdHis');
    }
}
