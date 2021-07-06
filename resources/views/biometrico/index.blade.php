@extends('layout.main')

@section('content')

<div class="container">
      <br />
      IMPORTAR CHECADAS
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <div class="row">
            <form action="{{url('import')}}" method="post" enctype="multipart/form-data">
              <div class="col-md-6">
                {{csrf_field()}}
                <input type="file" name="imported-file"/>
              </div>
              <div class="col-md-6">
                  <button class="btn btn-primary" type="submit">Importar Checadas</button>
              </div>
            </form>
          </div>
        </div>
        
      </div>
    </div>
    
@endsection

    
  