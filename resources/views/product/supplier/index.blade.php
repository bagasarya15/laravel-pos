@extends('layouts.main')

@section('menu-heading')
    Supplier
@endsection

@section('title')
    Data Supplier
@endsection

@section('content')

@include('layouts.sweet-alert')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('supplier.create') }}"class="btn btn-xs btn-primary block" > Tambah Supplier</a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>Nomer Tlp</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @foreach ($supplier as $supplier)</span></td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>{{ $supplier->number_phone }}</td>
                            <td>{{ $supplier->desc }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-xs btn-warning fa-solid fa-edit edit-supplier"></a> 
                                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="fa-solid fa-trash btn btn-xs btn-danger btn-delete mx-2" id="btn-delete"></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection