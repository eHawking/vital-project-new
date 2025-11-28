@extends('layouts.admin.app')

@section('title', __(' Pro Manual Order Rules Edit')) 

@section('content')
<div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
              
                {{translate('edit_manual_order_rules_for_pro_customers')}}
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
	
    {{-- Form to update the manual order rule --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4>Edit Rule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pro-manual-order.update', $rule->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="United Arab Emirates" {{ $rule->country == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                        <option value="Oman" {{ $rule->country == 'Oman' ? 'selected' : '' }}>Oman</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="min_retail_price">Min Retail Price</label>
                    <input type="number" name="min_retail_price" id="min_retail_price" step="0.01" class="form-control" value="{{ $rule->min_retail_price }}" required>
                </div>

                <div class="form-group">
                    <label for="max_retail_price">Max Retail Price (Optional)</label>
                    <input type="number" name="max_retail_price" id="max_retail_price" step="0.01" class="form-control" value="{{ $rule->max_retail_price }}">
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $rule->quantity }}" required>
                </div>

                <div class="form-group">
                    <label for="profit_amount">Profit Amount</label>
                    <input type="number" name="profit_amount" id="profit_amount" step="0.01" class="form-control" value="{{ $rule->profit_amount }}" required>
                </div>

                <div class="form-group">
                    <label for="max_profit">Max Profit (Optional)</label>
                    <input type="number" name="max_profit" id="max_profit" step="0.01" class="form-control" value="{{ $rule->max_profit }}">
                </div>

                <button type="submit" class="btn btn-primary">Update Rule</button>
            </form>
        </div>
    </div>
</div>

{{-- Toastr Notifications --}}
<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}', 'Success', { timeOut: 5000 });
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}', 'Error', { timeOut: 5000 });
    @endif
</script>

@endsection
