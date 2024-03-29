@extends('layouts.main')

@section('menu-heading')
    Produk    
@endsection

@section('title')
    Data Produk
@endsection

@section('content')

@include('layouts.sweet-alert')
    <section class="section">
        <div class="card">
            <form action="{{ route('product.delete_selected') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary block mt-1">Tambah Produk</a>
                <a href="{{ route('product.print') }}" class="btn btn-sm btn-success block mt-1" target="_blank">Print To Printer</a>
                <button type="submit" class="btn btn-danger btn-sm btn-delete block mt-1" > Hapus Data Yang Dipilih</button>
            </div>
            <div class="card-body table-responsive">
                <div class="custom-control custom-checkbox"><input  class="form-check-input form-check-primary" type="checkbox" name="select_all" id="select_all"> Pilih Semua</div>
                <table class="table dataTable1 table-hover">
                    <thead>
                        <tr class="small">
                            <th> # </th>
                            <th>Kode Produk</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga / Pcs</th>
                            <th>Stok</th>
                            <th>Image</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($product) > 0)
                        @foreach ($product as $product )
                        <tr class="small">
                            <td><input type="checkbox" name="id[{{ $product->id }}]" id="id" value="{{ $product->id }}" class="checkbox-delete"></td>
                            <td><a href="{{ route('product.show', $product->id) }}" class="badge bg-primary text-light">{{ $product->code_product }}</a></td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price_sell) }}</td>
                            <td>
                                @if ($product->stok == 0)
                                    <a href="{{ route('purchase.index') }}"><span class="text-danger">{{ 'Stok habis, lakukan re-stok produk' }}</span></a> 
                                @else
                                    {{ $product->stok }}
                                @endif
                            </td>
                            <td><img class="rounded-circle" width="45px" src="{{ asset('storage/'.$product ->image) }}" alt="Foto Produk"></td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('product.show', $product->id) }}"> <i class="fas fa-eye"></i>  Lihat Detail</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </form>
            </div>
        </div>
    </section>
@endsection