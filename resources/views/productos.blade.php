@extends('app')
@section('title', 'Productos')
@section('content')
    {{-- tabla productos --}}
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="float-right">
              <button class="btn btn-md btn-primary" onclick="getModal('1')">Agregar Producto</button>
                <br><br>
            </div>
            <div id="loading" style="display: none;">
              <div class="d-flex align-items-center float-left ml-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong>Loading...</strong>
              </div>
            </div>
          @if (count($productos)>0)
            <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Total Stock</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($productos as $producto)
                    <tr>
                      <td>{{ $producto['id'] }}</td>
                      <td>{{ $producto['nombre'] }}</td>
                      <td>{{ $producto['total'] }}</td>
                      <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="getProducto('{{ $producto['id'] }}')">Editar</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteProducto('{{ $producto['id'] }}')">Eliminar</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
          @else
              <div class="alert alert-dismissible fade show alert-danger mt-5" role="alert">
                <p>No hay productos registrados en el sistema</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
          @endif
        </div>
    </div>
    {{-- tabla productos --}}

    {{-- modal productos --}}
    <div class="modal fade" id="modal-producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" onclick="closeMoldal('1')">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form-producto" name="form-producto">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="nombre" class="col-form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
                <span id="error_nombre" style="color: red; font-size:large"></span>
              </div>
              <div class="form-group">
                <label for="total" class="col-form-label">Total:</label>
                <input type="text" name="total" id="total" class="form-control" required>
                <span id="error_total" style="color: red; font-size:large"></span>
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
    {{-- modal productos --}}

    {{-- modal productos delete --}}
    <div class="modal fade" id="modal-producto-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Producto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Desea usted, eliminar el Producto?</p>
          </div>
          <div class="modal-footer">
            <div id="deleting">
              <div class="d-flex align-items-center float-left mr-5 mt-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong class="ml-2">Loading...</strong>
              </div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="delete">Eliminar Producto</button>
          </div>
        </div>
      </div>
    </div>
    {{-- modal productos delete --}}
@endsection

@section('script')
    <script>
      $(document).ready(function() {
        $("#sending").hide();
        $("#loading").hide();
        $("#deleting").hide();
          clearForm();
          $("#form-producto").submit(function( event ) {
            event.preventDefault();

            var id   = $('#id').val();
            var data = $("#form-producto").serialize();

            if (id!=0) {
              $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('producto.update') }}",
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
                        toastr.success('Producto Actualizado Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_total').text(data.errors.total).fadeIn();
                        $('#error_nombre').text(data.errors.nombre).fadeIn();
                        setTimeout(function () {
                            $('#error_total').fadeOut();
                            $('#error_nombre').fadeOut();
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
                    url: "{{ route('producto.store') }}",
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
                        toastr.success('Producto Registrado Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_total').text(data.errors.total).fadeIn();
                        $('#error_nombre').text(data.errors.nombre).fadeIn();
                        setTimeout(function () {
                            $('#error_total').fadeOut();
                            $('#error_nombre').fadeOut();
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
          $('#exampleModalLabel').text('Nuevo Producto');
          $('#btn-form').text('Registrar');
        } else {
          $('#exampleModalLabel').text('Editar Producto');
          $('#btn-form').text('Actualizar');
        }
        $('#modal-producto').modal({backdrop: 'static', keyboard: false});
      }

      function closeMoldal(a) {
        if(a==1){
          $("#modal-producto").modal('hide');
        }else{
          $("#modal-producto-delete").modal('hide');
        }
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        clearForm();
      }

      function getProducto(id){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('producto') }}/"+id,
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
                $('#total').val(data.producto.total);
                $('#nombre').val(data.producto.nombre);
                $('#id').val(data.producto.id);
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

      function deleteProducto(id){
        $('#modal-producto-delete').modal({backdrop: 'static', keyboard: false});
        $('#delete').click(function(){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('producto') }}/"+id,
                method: "DELETE",
                beforeSend: function(){
                  $("#deleting").fadeIn();
                },
                complete:function(data){
                  $("#deleting").fadeOut();
                },
                success: function(data){
                  if(data.success){
                    toastr.success('Producto Eliminado Exitosamente', {timeOut: 3000});
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
        $('#total').val('');
        $('#nombre').val('');
        $('#id').val('0');
        $('#error_total').fadeOut();
        $('#error_nombre').fadeOut();
      }

    </script>
@endsection
