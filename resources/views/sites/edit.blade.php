@extends('voyager::master')

@section('content')
    <div class="page-content">
        <div class="content-container">
            <h1>Editar Site</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sites.update', $site->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="url">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" value="{{ $site->url }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        </div>
    </div>
@endsection
