<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Price List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: center;
        }
        .table th { background-color: #f5f5f5; }
        img { max-width: 50px; height: auto; }
    </style>
</head>
<body>
    <h2 class="text-center mb-4">Product Price List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>PV</th>
                <th>BV</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                      <!-- In price-list-pdf.blade.php -->
<!-- In price-list-pdf.blade.php -->
<img src="{{ asset('storage/product/' . $product->thumbnail) . '.webp' }}" 
     alt="{{ $product->name }}"
     style="width: 50px; height: 50px; object-fit: cover;"
     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pv }}</td>
                    <td>{{ $product->bv }}</td>
                    <td>{{ getCurrencySymbol(getCurrencyCode()) }} {{ number_format($product->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>