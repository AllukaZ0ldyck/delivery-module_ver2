@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Edit Order #{{ $order->id }}</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf

        <h5 class="fw-bold">Products</h5>

        <div id="items-wrapper">
            @foreach($order->items as $index => $item)
                <div class="d-flex gap-2 mb-2 align-items-center order-row">

                    <select name="items[{{ $index }}][product_id]" class="form-select" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                @if($product->id == $item->product_id) selected @endif>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" class="form-control"
                           name="items[{{ $index }}][quantity]" 
                           value="{{ $item->quantity }}"
                           min="1" required>

                    <button type="button" class="btn btn-danger remove-row">X</button>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-row">+ Add Item</button>

        <h5 class="fw-bold mt-4">Delivery Date</h5>
        <input type="date" name="delivery_date" class="form-control" 
               value="{{ $order->delivery_date }}" required>

        <button class="btn btn-primary mt-4">Save Changes</button>
    </form>

</div>
<script>
    const products = @json($products);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const products = @json($products);
    let index = {{ count($order->items) }};

    // Add row
    document.getElementById('add-row').addEventListener('click', () => {

        let options = '';
        products.forEach(p => {
            options += `<option value="${p.id}">${p.name}</option>`;
        });

        let html = `
            <div class="d-flex gap-2 mb-2 align-items-center order-row">
                <select name="items[${index}][product_id]" class="form-select" required>
                    ${options}
                </select>

                <input type="number" name="items[${index}][quantity]"
                    class="form-control" min="1" value="1" required>

                <button type="button" class="btn btn-danger remove-row">X</button>
            </div>
        `;

        document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', html);
        index++;
    });

    // Remove row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.order-row').remove();
        }
    });

});
</script>

</script>
@endsection
