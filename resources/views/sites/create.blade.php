@extends('voyager::master')

@section('content')
    <div class="page-content">
        <div class="content-container">
            <h1>Adicionar Site</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sites.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="url">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div>
                <button type="submit" class="btn btn-success">Adicionar</button>
            </form>
        </div>
    </div>
@endsection
