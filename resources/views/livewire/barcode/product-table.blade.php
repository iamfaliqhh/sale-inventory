<div>
    @if (session()->has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span>{{ session('message') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr class="align-middle">
                        <th class="align-middle">Product Name</th>
                        <th class="align-middle">Code</th>
                        <th class="align-middle">
                            Quantity <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="Max Quantity: 100"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @if(!empty($product))
                            <td class="align-middle">{{ $product->product_name }}</td>
                            <td class="align-middle">{{ $product->product_code }}</td>
                            <td class="align-middle text-center" style="width: 200px;">
                                <input wire:model.live="quantity" class="form-control" type="number" min="1" max="100" value="{{ $quantity }}">
                            </td>
                        @else
                            <td colspan="3" class="text-center">
                                <span class="text-danger">Please search & select a product!</span>
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
