@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Place New Order</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div id="order-items">
            <div class="order-item mb-3 border p-3 rounded">
                <label>Product</label>
                <select name="items[0][product_id]" class="form-control mb-2" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - ₱{{ number_format($product->price, 2) }}
                        </option>
                    @endforeach
                </select>

                <label>Quantity</label>
                <input type="number" name="items[0][quantity]" class="form-control mb-2" min="1" value="1" required>

                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            </div>
        </div>

        <button type="button" id="add-item" class="btn btn-outline-primary mb-3">+ Add Another Product</button>

        <div class="mb-3">
            <label>Delivery Address</label>
            <textarea name="delivery_address" class="form-control" rows="3" required>{{ old('delivery_address', $user->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Preferred Delivery Date</label>
            <input type="date" name="delivery_date" class="form-control" required>
        </div>

        {{-- Payment Method --}}
        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="">-- Select Payment Method --</option>
                <option value="COD">Cash on Delivery</option>
                <option value="GCash">GCash</option>
            </select>
        </div>

        <div class="mb-3" id="gcash-upload" style="display:none;">
            <label>Upload GCash Receipt</label>
            <input type="file" name="payment_receipt" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Place Order</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const addBtn = document.getElementById('add-item');
    const container = document.getElementById('order-items');

    addBtn.addEventListener('click', function() {
        // clone the first .order-item
        const template = container.querySelector('.order-item');
        const clone = template.cloneNode(true);

        // Update names and reset values
        clone.querySelectorAll('select, input').forEach((el) => {
            // product select
            if (el.name && el.name.includes('product_id')) {
                el.name = `items[${itemIndex}][product_id]`;
                el.selectedIndex = 0; // reset select
            }

            // quantity input
            if (el.name && el.name.includes('quantity')) {
                el.name = `items[${itemIndex}][quantity]`;
                el.value = 1;
            }
        });

        container.appendChild(clone);
        itemIndex++;
        updateRemoveButtons();
    });

    // Remove item (delegated)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const items = document.querySelectorAll('.order-item');
            if (items.length > 1) {
                e.target.closest('.order-item').remove();
            } else {
                // if only 1 left, just reset fields instead of removing
                const sel = document.querySelector('.order-item select');
                const qty = document.querySelector('.order-item input[type="number"]');
                if (sel) sel.selectedIndex = 0;
                if (qty) qty.value = 1;
            }
            updateRemoveButtons();
        }
    });

    // show/hide gcash upload
    const paymentMethod = document.getElementById('payment_method');
    const gcashUpload = document.getElementById('gcash-upload');
    paymentMethod.addEventListener('change', function() {
        gcashUpload.style.display = this.value === 'GCash' ? 'block' : 'none';
    });

    function updateRemoveButtons() {
        const removeBtns = document.querySelectorAll('.remove-item');
        // enable remove only when more than 1 item
        if (removeBtns.length <= 1) {
            removeBtns.forEach(b => b.disabled = true);
        } else {
            removeBtns.forEach(b => b.disabled = false);
        }
    }

    // initial state
    updateRemoveButtons();
});
</script>
@endsection
