@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center">
    <form action="#" method="POST" id="product-create">
        @csrf
        <div class="d-flex flex-row justify-content-around w-100 align-items-center mt-3">
            <div class="form-group">
                <label for="">Product Name</label>
                <input class="form-control" type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="">Price per Item</label>
                <input class="form-control" type="number" step=".01" value="0" name="price_per_item" id="price_per_item" required>
            </div>
            <div class="form-group">
                <label for="">Quantity in Stock</label>
                <input class="form-control" type="number" step="1" value="0" name="quantity_stock" id="quantity_stock" required>
            </div>
            <div>
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </div>
    </form>

    <div class="d-flex flex-row justify-content-around w-100 align-items-center mt-3">
        <table class="table table-striped"> 
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Submitted at</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        <td>{{$product->quantity_stock}}</td>
                        <td>USD {{money_format('%i', $product->price_per_item)}}</td>
                        <td>{{$product->created_at->format('mm/D/Y')}}</td>
                        <td>{{$product->price_per_item * $product->quantity_stock}}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5">No Products Registered</td>
                    </tr> 
                @endif
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $('#product-create').on('submit', function(event) {
        event.preventDefault();
        
    })
</script>
@endsection