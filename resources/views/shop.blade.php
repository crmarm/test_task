@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

 <style>
        body {
            color: #404E67;
            background: #F5F7FA;
            font-family: 'Open Sans', sans-serif;
        }
        .table-wrapper {
            width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }
        .table-title {
            padding-bottom: 10px;
            margin: 0 0 10px;
        }
        .table-title h2 {
            margin: 6px 0 0;
            font-size: 22px;
        }
        .table-title .add-new {
            float: right;
            height: 30px;
            font-weight: bold;
            font-size: 12px;
            text-shadow: none;
            min-width: 100px;
            border-radius: 50px;
            line-height: 13px;
        }
        .table-title .add-new i {
            margin-right: 4px;
        }
        table.table {
            table-layout: fixed;
        }
        table.table tr th, table.table tr td {
            border-color: #e9e9e9;
        }
        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }
        table.table th:last-child {
            width: 100px;
        }
        table.table td a {
            cursor: pointer;
            display: inline-block;
            margin: 0 5px;
            min-width: 24px;
        }
        table.table td a.add {
            color: #27C46B;
        }
        table.table td a.edit {
            color: #FFC107;
        }
        table.table td a.delete {
            color: #E34724;
        }
        table.table td i {
            font-size: 19px;
        }
        table.table td a.add i {
            font-size: 24px;
            margin-right: -1px;
            position: relative;
            top: 3px;
        }
        table.table .form-control {
            height: 32px;
            line-height: 32px;
            box-shadow: none;
            border-radius: 2px;
        }
        table.table .form-control.error {
            border-color: #f50000;
        }
        table.table td .add {
            display: none;
        }
    </style>
<div class="container-lg">

   @if($errors)
{{--          <span class="btn-outline-danger">{{$errors['message']}}</span> <br>--}}
    @endif

    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-4"><h2>Products</h2></div>
                    <div class="col-sm-4">
                        <form action="{{ route('products.search') }}" method="GET">
                            <input type="text" name="search" required/>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
            <form class="product-update" action="{{ route('products.update') }}" method="Post">
                @csrf
                <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Expiration date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)

                        <tr>
                            <td class="d-none" data-name="id">{{$product->id}}</td>
                            <td data-name="name">{{$product->name}}</td>
                            <td data-name="price">{{$product->price}}</td>
                            <td data-name="quantity">{{$product->quantity}}</td>
                            <td data-name="expiration_date">{{$product->expiration_date}}</td>
                            <td>
                                <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                                <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>

                                <a class="delete" onclick="return confirm('Are you sure?')" href="{{route('product.delete', $product->id)}}"><i class="material-icons">&#xE872;</i></a>
{{--                                <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>--}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </form>
        </div>
        <?php
        if(!isset($search)){
                $search = '';
               }
        ?>
        <span data-href="{{route('products.csv',['search'=> $search])}}" id="export" class="btn btn-success btn-sm mb-5" onclick="exportTasks(event.target);">Export as CSV</span>
    </div>
</div>
<script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            var actions = $("table td:last-child").html();
            // Append table with add row form on add new button click
            $(".add-new").click(function(){
                $(this).attr("disabled", "disabled");
                var index = $("table tbody tr:last-child").index();
                var row = '<tr>' +
                    '<td><input type="text" class="form-control" name="name" id="name"></td>' +
                    '<td><input type="text" class="form-control" name="price" id="price"></td>' +
                    '<td><input type="text" class="form-control" name="quantity" id="quantity"></td>' +
                    '<td><input type="date" class="form-control" name="expiration_date" id="expiration_date"></td>' +
                    // '<td>' + actions + '</td>' +
                    '<td> <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a></td>' +
                    '</tr>';
                $("table").append(row);
                $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
                $('[data-toggle="tooltip"]').tooltip();
            });
            // Add row on add button click
            $(document).on("click", ".add", function(){
                $('.product-update').submit();
                var empty = false;
                var input = $(this).parents("tr").find('input[type="text"]');
                input.each(function(){
                    if(!$(this).val()){
                        $(this).addClass("error");
                        empty = true;
                    } else{
                        $(this).removeClass("error");
                    }
                });
                $(this).parents("tr").find(".error").first().focus();
                if(!empty){
                    input.each(function(){
                        $(this).parent("td").html($(this).val());
                    });
                    $(this).parents("tr").find(".add, .edit").toggle();
                    $(".add-new").removeAttr("disabled");
                }

            });
            // Edit row on edit button click
            $(document).on("click", ".edit", function(){
                $(this).parents("tr").find("td:not(:last-child)").each(function(){
                    $(this).html('<input type="text" name="' + $(this).attr('data-name') + '"  class="form-control" value="' + $(this).text() + '">');
                });
                $(this).parents("tr").find(".add, .edit").toggle();
                $(".add-new").attr("disabled", "disabled");

            });
            // Delete row on delete button click
            $(document).on("click", ".delete", function(){
                $(this).parents("tr").remove();
                $(".add-new").removeAttr("disabled");
            });




        });
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }


    </script>
@endsection
