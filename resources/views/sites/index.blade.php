@extends('voyager::master')

@section('content')

<div class="page-content"> <div class="content-container">
<div class="row align-items-center">

<div class="col-md-6">
  <h1>Lista de Sites</h1>
</div>

<div class="col-md-6 d-flex justify-content-end">
  
</div>

</div>

<div class="mt-3">
  
  
  </div>
  
  <div class="input-group-append">
  
  <form action="{{ route('sites.index') }}" method="GET">
  
  <div class="input-group">
  
    <input type="text" class="form-control" name="search" id="search" value="{{ request('search') }}" placeholder="Buscar por URL...">
    
    
  </div>
  
</form>
<button type="submit" class="btn btn-primary">Buscar</button> 


</div>
<a href="{{ route('sites.create') }}" class="btn btn-success">Adicionar Site</a>
<table class="table mt-4">

<thead class="thead-light">

  <tr>
  
    <th>ID</th>
    
    <th>URL</th>
    
    <th>Ações</th>
    
  </tr>
  
</thead>

<tbody>

  @forelse($sites as $site)

    <tr class="table-active">
    
      <td>{{ $site->id }}</td> 
      
      <td>{{ $site->url }}</td>
      
      <td class="text-right">
      
        <a href="{{ route('sites.edit', $site->id) }}" class="btn btn-primary btn-sm">Editar</a>
        
        <form action="{{ route('sites.destroy', $site->id) }}" method="POST" class="d-inline">
        
          @csrf
          @method('DELETE')
          
          <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
          
        </form>
        
      </td>
      
    </tr>
    
  @empty
    
    <tr>
    
      <td colspan="3" class="text-center">Nenhum site encontrado.</td>
      
    </tr>
    
  @endforelse
  
</tbody>

</table>

<div class="mt-4 text-center">

{{ $sites->links() }}

</div>

</div> </div>
@endsection