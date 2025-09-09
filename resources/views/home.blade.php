@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="lead">Ini adalah dashboard pribadi Anda di Website Galeri Foto.</p>
            <hr class="my-4">
            <p>Anda telah mengunggah {{ Auth::user()->photos->count() }} foto ke galeri.</p>
            <a class="btn btn-primary btn-lg" href="{{ route('gallery.create') }}" role="button">
                <i class="bi bi-plus-circle"></i> Tambah Foto Baru
            </a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col">
        <h3>Foto Saya</h3>
        @if (Auth::user()->photos->count() > 0)
            <div class="row">
                @foreach (Auth::user()->photos as $photo)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset($photo->image_path) }}" class="card-img-top gallery-img" alt="{{ $photo->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $photo->title }}</h5>
                                <a href="{{ route('gallery.show', $photo->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Anda belum mengunggah foto apa pun. <a href="{{ route('gallery.create') }}">Unggah foto pertama Anda sekarang!</a>
            </div>
        @endif
    </div>
</div>
@endsection
