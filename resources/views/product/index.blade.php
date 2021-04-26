@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center">
    <form class="w-100" action="{{route('product.store')}}" method="POST" id="product-create">
        @csrf
        <div class="d-flex flex-row justify-content-around w-100 align-items-center mt-3">
            <div class="form-group">
                <label for="">Product Name</label>
                <input class="form-control" type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="">Price per Item</label>
                <input class="form-control" type="number" step=".01" value="0" name="price_per_item" id="price_per_item" min="0" max="99999999" required>
            </div>
            <div class="form-group">
                <label for="">Quantity in Stock</label>
                <input class="form-control" type="number" step="1" value="0" name="quantity_stock" id="quantity_stock"  min="0" max="99999999" required>
            </div>
            <div>
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </div>
    </form>

    <div class="d-flex flex-row justify-content-around w-100 align-items-center mt-3">
        <table class="table table-striped" id="product-table"> 
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Submitted at</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($products) > 0)
                    @php
                        $totalValue = 0;
                    @endphp
                    @foreach ($products as $product)
                    @php
                        $totalValue += $product->price_per_item * $product->quantity_stock;
                    @endphp
                    <tr class="product-row" data-id="{{$product->id}}">
                        <td>{{$product->name}}</td>
                        <td>{{$product->quantity_stock}}</td>
                        <td>USD {{$product->price_per_item}}</td>
                        <td>{{$product->created_at->format('m/d/Y')}}</td>
                        <td>USD {{$product->price_per_item * $product->quantity_stock}}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-edit"  data-toggle="modal" data-target="#edit-modal" data-id="{{$product->id}}" data-get-url="{{route('product.show', $product->id)}}" data-edit-url="{{route('product.update', $product->id)}}" onclick="editProduct({{$product->id}})">Edit</button>
                            {{-- <button class="btn btn-danger btn-delete" data-id="{{$product->id}}">Delete</button> --}}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-left" colspan="4">Total Value</td>
                        <td class="text-left" colspan="1">USD {{$totalValue}}</td>
                        <td class="text-center" colspan="1"></td>
                    </tr> 
                @else
                    <tr>
                        <td class="text-center" colspan="5">No Products Registered</td>
                    </tr> 
                @endif
            </tbody>
        </table>
    </div>

    <div class="modal" tabindex="-1" id="edit-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Product</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="w-100" action="#" method="POST" id="product-edit">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-row justify-content-around w-100 align-items-center mt-3">
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input class="form-control" type="text" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="">Price per Item</label>
                            <input class="form-control" type="number" step=".01" value="0" name="price_per_item" id="price_per_item" min="0" max="99999999" required>
                        </div>
                        <div class="form-group">
                            <label for="">Quantity in Stock</label>
                            <input class="form-control" type="number" step="1" value="0" name="quantity_stock" id="quantity_stock"  min="0" max="99999999" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" form="product-edit">Save changes</button>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection

@section('scripts')
<script>
    $('#product-create').on('submit', function(event) {
        event.preventDefault();
        const form = $(this);

        var data = form.serialize();

        $.ajax(form.attr('action'), {
            data: data,
            type: "post",
            success: function(response){
                var response = JSON.parse(response);

                var productRow = '<tr class="product-row" data-id="'+ response.product.id +'">';
                productRow += '<td>'+ response.product.name +'</td>';
                productRow += '<td>'+ response.product.quantity_stock +'</td>';
                productRow += '<td>USD '+ response.product.price_per_item +'</td>';
                productRow += '<td>'+ new Date(response.product.created_at).toLocaleDateString() +'</td>';
                productRow += '<td>USD '+ (response.product.price_per_item * response.product.quantity_stock) +'</td>';
                productRow += '<td><button class="btn btn-warning btn-edit" data-id="'+response.product.id+'" data-toggle="modal" data-target="#edit-modal" data-get-url="'+response.getUrl+'" data-edit-url="'+response.editURL+'" onclick="editProduct('+response.product.id+')">Edit</button></td>';
                // productRow += '<button class="btn btn-danger" data-id="'+response.product.id+'">Delete</button></td>';
                        
                $('#product-table').prepend(productRow);
            }
        });
    })

    function editProduct(id) {    
        const button = $('.btn-edit[data-id="'+ id +'"]');
        const form = $('#product-edit');

        const getUrl = button.attr('data-get-url');
        const editUrl = button.attr('data-edit-url');

        console.log(id, button, getUrl, editUrl);

        $.ajax(getUrl, {
            type: "get",
            success: function(response){
                var response = JSON.parse(response);

                console.log(response);

                form.find('#name').val(response.product[0].name);
                form.find('#price_per_item').val(response.product[0].price_per_item);
                form.find('#quantity_stock').val(response.product[0].quantity_stock);
                        
                form.attr('action', editUrl);
            }
        });
    }

    $('#product-edit').on('submit', function(event) {
        event.preventDefault();
        const form = $(this);

        var data = form.serialize();

        $.ajax(form.attr('action'), {
            data: data,
            type: "post",
            success: function(response){
                var response = JSON.parse(response);


                var editedRow = $('#product-table').find('tr[data-id="'+response.product.id+'"]');
                editedRow.empty();

                var productRow = '<td>'+ response.product.name +'</td>';
                productRow += '<td>'+ response.product.quantity_stock +'</td>';
                productRow += '<td>USD '+ response.product.price_per_item +'</td>';
                productRow += '<td>'+ new Date(response.product.created_at).toLocaleDateString() +'</td>';
                productRow += '<td>USD '+ (response.product.price_per_item * response.product.quantity_stock) +'</td>';
                productRow += '<td><button class="btn btn-warning btn-edit" data-id="'+response.product.id+'" data-toggle="modal" data-target="#edit-modal" data-get-url="'+response.getUrl+'" data-edit-url="'+response.editURL+'" onclick="editProduct('+response.product.id+')">Edit</button></td>';
                // productRow += '<button class="btn btn-danger" data-id="'+response.product.id+'">Delete</button></td>';
                        
                $(editedRow).prepend(productRow);

                $('#edit-modal').modal('toggle');
            }
        });
    })
</script>
@endsection