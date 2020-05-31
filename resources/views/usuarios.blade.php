@extends('app')
@section('title', 'Usuarios')
@section('content')
    {{-- tabla usuarios --}}
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="float-right">
              <button class="btn btn-md btn-primary" onclick="getModal('1')">Agregar Usuario</button>
                <br><br>
            </div>
            <div id="loading" style="display: none;">
              <div class="d-flex align-items-center float-left ml-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong>Loading...</strong>
              </div>
            </div>
            @if (count($usuarios)>0)
                <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($usuarios as $usuario)
                        <tr>
                          <td>{{ $usuario->id }}</td>
                          <td>{{ $usuario->documento }}</td>
                          <td>{{ $usuario->nombre }}</td>
                          <td>{{ $usuario->apellido }}</td>
                          <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="getUsuario('{{ $usuario->id }}')">Editar</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteUsuario('{{ $usuario->id }}')">Eliminar</button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              @else
                  <div class="alert alert-dismissible fade show alert-danger mt-5" role="alert">
                    <p>No hay usuarios registrados en el sistema</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
              @endif
        </div>
    </div>
    {{-- tabla usuarios --}}

    {{-- modal usuarios --}}
    <div class="modal fade" id="modal-usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" onclick="closeMoldal('1')">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form-usuario" name="form-usuario">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="documento" class="col-form-label">Documento:</label>
                <input type="text" name="documento" id="documento" class="form-control" required>
                <span id="error_documento" style="color: red; font-size:large"></span>
              </div>
              <div class="form-group">
                <label for="nombre" class="col-form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
                <span id="error_nombre" style="color: red; font-size:large"></span>
              </div>
              <div class="form-group">
                <label for="apellido" class="col-form-label">Apellido:</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required>
                <span id="error_apellido" style="color: red; font-size:large"></span>
              </div>
              <div id="sending">
                <div class="d-flex align-items-center float-left ml-2">
                  <div class="spinner-border ml-auto" role="status"></div>
                  <strong class="ml-2">Loading...</strong>
                </div>
              </div>
              <button class="btn btn-primary float-right mb-2" type="submit" id="btn-form"></button>
              <button type="button" class="btn btn-secondary float-right mr-2" onclick="closeMoldal('1')">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    {{-- modal usuarios --}}

    {{-- modal usuarios delete --}}
    <div class="modal fade" id="modal-usuario-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Desea usted, eliminar el usuario?</p>
          </div>
          <div class="modal-footer">
            <div id="deleting">
              <div class="d-flex align-items-center float-left mr-5 mt-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong class="ml-2">Loading...</strong>
              </div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="delete">Eliminar Usuario</button>
          </div>
        </div>
      </div>
    </div>
    {{-- modal usuarios delete --}}
@endsection

@section('script')
    <script>
      $(document).ready(function() {
        $("#sending").hide();
        $("#loading").hide();
        $("#deleting").hide();
          clearForm();
          $("#form-usuario").submit(function( event ) {
            event.preventDefault();

            var id   = $('#id').val();
            var data = $("#form-usuario").serialize();

            if (id!=0) {
              $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('usuario.update') }}",
                    method: "PUT",
                    data:data,
                    beforeSend: function(){
                      $("#sending").fadeIn();
                    },
                    complete:function(data){
                      $("#sending").fadeOut();
                    },
                    success: function(data){
                      if(data.success){
                        toastr.success('Usuario Actualizado Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_documento').text(data.errors.documento).fadeIn();
                        $('#error_nombre').text(data.errors.nombre).fadeIn();
                        $('#error_apellido').text(data.errors.apellido).fadeIn();
                        setTimeout(function () {
                            $('#error_documento').fadeOut();
                            $('#error_nombre').fadeOut();
                            $('#error_apellido').fadeOut();
                         }, 5000);
                        swal("Error", 'Verifique, por favor!', "error");
                      }
                      console.log(data);
                    },
                    error: function(e){
                      console.log(e);
                    }
                });
            }else {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('usuario.store') }}",
                    method: "POST",
                    data:data,
                    beforeSend: function(){
                      $("#sending").fadeIn();
                    },
                    complete:function(data){
                      $("#sending").fadeOut();
                    },
                    success: function(data){
                      if(data.success){
                        toastr.success('Usuario Registrado Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_documento').text(data.errors.documento).fadeIn();
                        $('#error_nombre').text(data.errors.nombre).fadeIn();
                        $('#error_apellido').text(data.errors.apellido).fadeIn();
                        setTimeout(function () {
                            $('#error_documento').fadeOut();
                            $('#error_nombre').fadeOut();
                            $('#error_apellido').fadeOut();
                         }, 5000);
                        swal("Error", 'Verifique, por favor!', "error");
                      }
                      console.log(data);
                    },
                    error: function(e){
                      console.log(e);
                    }
                });
            }
              return false;
          });
      });

      function getModal(a){
        if (a==1) {
          $('#exampleModalLabel').text('Nuevo Usuario');
          $('#btn-form').text('Registrar');
        } else {
          $('#exampleModalLabel').text('Editar Usuario');
          $('#btn-form').text('Actualizar');
        }
        $('#modal-usuario').modal({backdrop: 'static', keyboard: false});
      }

      function closeMoldal(a) {
        if(a==1){
          $("#modal-usuario").modal('hide');
        }else{
          $("#modal-usuario-delete").modal('hide');
        }
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        clearForm();
      }

      function getUsuario(id){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('usuario') }}/"+id,
            method: "POST",
            beforeSend: function(){
              $("#loading").fadeIn();
            },
            complete:function(data){
              $("#loading").fadeOut();
            },
            success: function(data){
              if(data.success){
                getModal('2');
                $('#documento').val(data.usuario.documento);
                $('#nombre').val(data.usuario.nombre);
                $('#apellido').val(data.usuario.apellido);
                $('#id').val(data.usuario.id);
                console.log(data);
              }else{
                console.log('e');
              }
            },
            error: function(e){
              console.log(e);
            }
        });
      }

      function deleteUsuario(id){
        $('#modal-usuario-delete').modal({backdrop: 'static', keyboard: false});
        $('#delete').click(function(){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('usuario') }}/"+id,
                method: "DELETE",
                beforeSend: function(){
                  $("#deleting").fadeIn();
                },
                complete:function(data){
                  $("#deleting").fadeOut();
                },
                success: function(data){
                  if(data.success){
                    toastr.success('Usuario Eliminado Exitosamente', {timeOut: 3000});
                    setTimeout(function () { location.href = window.location.href }, 3000);
                    closeMoldal('2');
                    console.log(data);
                  }else{
                    console.log('e');
                  }
                },
                error: function(e){
                  console.log(e);
                }
            });
        });
      }

      function clearForm(){
        $('#documento').val('');
        $('#nombre').val('');
        $('#apellido').val('');
        $('#id').val('0');
        $('#error_documento').fadeOut();
        $('#error_nombre').fadeOut();
        $('#error_apellido').fadeOut();
      }

    </script>
@endsection
