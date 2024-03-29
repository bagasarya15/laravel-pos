@extends('layouts.main')

@section('menu-heading')
    Member    
@endsection

@section('title')
  Edit Member
@endsection

@section('content')

@include('layouts.sweet-alert')
<div class="card">
    <div class="card-header">
        <a href="{{ route('member.index') }}"><i class="fa-solid fa-angles-left"></i> Kembali</a>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{ route('member.update', $member) }}" method="POST" class="form form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Kode Member</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" id="code_member" class="form-control bg-light" name="code_member" value="{{ old('code_member', $member->code_member) }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Nama</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" id="name" class="form-control" name="name"
                            placeholder="Nama" autocomplete="off" value="{{ old('name', $member->name) }}">
                        </div>
                        <div class="col-md-4">
                            <label>Alamat</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <textarea class="form-control" id="address" name="address" placeholder="Alamat" rows="3">{{ old('address', $member->address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label>No Tlp</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="number" id="number_phone" class="form-control" name="number_phone"
                                placeholder="No Tlp" autocomplete="off" value="{{ old('number_phone', $member->number_phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label>Status Member</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <select class="form-select" id="basicSelect" name="member_status">
                            @if ($member->member_status == 'Non-Member')
                                <option value="Non-Member">Non-Member</option>
                                <option value="Member">Member</option>
                            @endif
                            
                            @if ($member->member_status == 'Member')
                                <option value="Member">Member</option>
                                <option value="Non-Member">Non-Member</option>
                            @endif
                        </select>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary mb-1 mx-2"><i class="fa-solid fa-edit"></i> Update</button>
                            <button type="reset" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-arrows-rotate"></i> Reset </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection