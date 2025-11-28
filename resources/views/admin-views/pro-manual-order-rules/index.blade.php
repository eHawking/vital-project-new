@extends('layouts.admin.app')

@section('title', __('Pro Manual Order Rules'))

@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            {{ translate('manage_manual_order_rules_for_pro_customers') }}
        </h2>
    </div>

    {{-- Display success or error messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to create or update a manual order rule --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4>Create New Rule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pro-manual-order.store') }}" method="POST">
                @csrf
			<div class="row">
				<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="Pakistan">Pakistan</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United States">United States of America</option>
<option value="United Kingdom">United Kingdom</option>
<option value="Canada">Canada</option>
<option value="Australia">Australia</option>

                    </select>
                </div>
					</div>

					<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="min_retail_price">Min Retail Price</label>
                    <input type="number" name="min_retail_price" id="min_retail_price" step="0.01" class="form-control" required>
                </div>
						</div>

						<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="max_retail_price">Max Retail Price (Optional)</label>
                    <input type="number" name="max_retail_price" id="max_retail_price" step="0.01" class="form-control">
                </div>
							</div>

							<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>
								</div>

								<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="profit_amount">Profit Amount</label>
                    <input type="number" name="profit_amount" id="profit_amount" step="0.01" class="form-control" required>
                </div>
									</div>

									<div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="max_profit">Max Profit (Optional)</label>
                    <input type="number" name="max_profit" id="max_profit" step="0.01" class="form-control">
                </div>
										</div>
			</div>

                <button type="submit" class="btn btn-primary">Create Rule</button>
            </form>
        </div>
    </div>

    {{-- Table displaying all existing manual order rules --}}
    <div class="card">
        <div class="card-header">
            <h4>Existing Manual Order Rules</h4>
        </div>
        <div class="card-body">
			<div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th>Min Retail Price</th>
                        <th>Max Retail Price</th>
                        <th>Quantity</th>
                        <th>Profit Amount</th>
                        <th>Max Profit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rules as $rule)
                        <tr>
                            <td>{{ $rule->country }}</td>
                            <td>{{ number_format($rule->min_retail_price, 2) }}</td>
                            <td>{{ $rule->max_retail_price ? number_format($rule->max_retail_price, 2) : 'N/A' }}</td>
                            <td>{{ $rule->quantity }}</td>
                            <td>{{ number_format($rule->profit_amount, 2) }}</td>
                            <td>{{ $rule->max_profit ? number_format($rule->max_profit, 2) : 'N/A' }}</td>
                            <td>
                                {{-- Edit Button --}}
                                <a href="{{ route('admin.pro-manual-order.edit', $rule->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.pro-manual-order.delete', $rule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this rule?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No manual order rules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
				</div>
        </div>
    </div>
</div>
@endsection
