<div>
    @if($page == "Trade In")
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Trade In Product Form</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="addTradeInProduct">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="trade_in_product_name">Product Name <span class="text-danger">*</span></label>
                            <input wire:model="trade_in_product_name" type="text" id="trade_in_product_name" class="form-control" placeholder="Enter product name">
                            @error('trade_in_product_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="trade_in_product_code">Product Code <span class="text-danger">*</span></label>
                            <input wire:model="trade_in_product_code" type="text" id="trade_in_product_code" class="form-control" placeholder="Enter product code">
                            @error('trade_in_product_code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="trade_in_product_weight">Weight (grams) <span class="text-danger">*</span></label>
                            <input wire:model.blur="trade_in_product_weight" type="number" id="trade_in_product_weight" class="form-control" placeholder="0.00" step="0.01" min="0.01">
                            @error('trade_in_product_weight') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="trade_in_product_purity">Purity (%) <span class="text-danger">*</span></label>
                            <input wire:model.blur="trade_in_product_purity" type="number" id="trade_in_product_purity" class="form-control" placeholder="0.00" min="1" max="100" step=".01">
                            @error('trade_in_product_purity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estimated Value</label>
                            <div class="form-control-plaintext font-weight-bold text-success">
                                {{ format_currency($trade_in_total_value) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <button type="button" wire:click="resetTradeInForm" class="btn btn-secondary mr-2">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Trade In Product
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
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

                <div class="form-group">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i>
                            </a>
                        </div>
                        <select wire:model.live="customer_id" id="customer_id" class="form-control">
                            <option value="" selected>Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($page == "Transaction")
                <div class="form-group">
                    <label for="sales_person_id">Sales Person <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="{{ route('sales_person.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i>
                            </a>
                        </div>
                        <select wire:model.live="sales_person_id" id="sales_person_id" class="form-control">
                            <option value="" selected>Select Sales Person</option>
                            @foreach($sales_persons as $sales_person)
                                <option value="{{ $sales_person->id }}">{{ $sales_person->sales_person_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr class="text-center">
                            <th class="align-middle">Product</th>
                            <th class="align-middle">Price</th>
                            <th class="align-middle">Quantity</th>
                            <th class="align-middle">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($cart_items->isNotEmpty())
                            @foreach($cart_items as $cart_item)
                                <tr class="{{ isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in ? 'table-success' : '' }}">
                                    <td class="align-middle">
                                        {{ $cart_item->name }}
                                        @if(isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in)
                                            <span class="badge badge-warning ml-1">Trade In</span>
                                        @endif
                                        <br>
                                        <span class="badge badge-success">
                                        {{ $cart_item->options->code }}
                                        </span>
                                        @if(!isset($cart_item->options->is_trade_in) || !$cart_item->options->is_trade_in)
                                            @include('livewire.includes.product-cart-modal')
                                        @endif
                                    </td>

                                    <td class="align-middle {{ isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in ? 'text-success font-weight-bold' : '' }}">
                                        @if(isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in)
                                            {{ format_currency($cart_item->price) }}
                                            <small class="d-block text-muted">{{ $cart_item->weight }}g x {{ $cart_item->options->product_purity }}%</small>
                                            <small class="d-block text-info">Credit Value</small>
                                        @else
                                            {{ format_currency($cart_item->price) }}
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @if(isset($cart_item->options->is_trade_in) && $cart_item->options->is_trade_in)
                                            <span class="badge badge-info">1</span>
                                        @else
                                            @include('livewire.includes.product-cart-quantity')
                                        @endif
                                    </td>

                                    <td class="align-middle text-center">
                                        <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                            <i class="bi bi-x-circle font-2xl text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    <span class="text-danger">
                                        Please search & select products!
                                    </span>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Subtotal</th>
                                @php
                                    $cart = Cart::instance($cart_instance);
                                    $subtotal = 0;
                                    foreach ($cart->content() as $item) {
                                        $subtotal += ($item->price * $item->qty);
                                    }
                                @endphp
                                <td>{{ format_currency($subtotal) }}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>(-) {{ format_currency($global_discount) }}</td>
                            </tr>
                            <tr>
                                <th>Shipping</th>
                                <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                <td>(+) {{ format_currency($shipping) }}</td>
                            </tr>
                            <tr>
                                <th>Wage</th>
                                <input type="hidden" value="{{ $wage }}" name="wage_amount">
                                <td>(+) {{ format_currency($wage) }}</td>
                            </tr>
                            <tr class="text-primary">
                                <th>Grand Total</th>
                                @php
                                    $total_with_shipping = $total_amount;
                                @endphp
                                <th>
                                    (=) {{ format_currency($total_with_shipping) }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="discount_amount">Discount (Amount)</label>
                        <input wire:model.blur="global_discount" type="number" class="form-control" min="0" value="{{ $global_discount }}" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="shipping_amount">Shipping</label>
                        <input wire:model.blur="shipping" type="number" class="form-control" min="0" value="0" required step="0.01">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="wage_amount">Wage</label>
                        <input wire:model.blur="wage" type="number" class="form-control" min="0" value="{{ $wage }}" required step="0.01">
                    </div>
                </div>
            </div>

            <div class="form-group d-flex justify-content-center flex-wrap mb-0">
                <button wire:click="resetCart" type="button" class="btn btn-pill btn-danger mr-3"><i class="bi bi-x"></i> Reset</button>
                <button wire:loading.attr="disabled" wire:click="proceed" type="button" class="btn btn-pill btn-primary" {{  $total_amount == 0 ? 'disabled' : '' }}><i class="bi bi-check"></i> Proceed</button>
            </div>
        </div>
    </div>

    {{--Checkout Modal--}}
    @include('livewire.pos.includes.checkout-modal')

</div>
