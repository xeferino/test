@extends('app')
@section('title', 'Guías')
@section('content')
    {{-- tabla guias --}}
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="float-right">
              <button class="btn btn-md btn-primary" onclick="getLast('1')">Agregar Guías</button>
                <br><br>
            </div>
            <div id="loading" style="display: none;">
              <div class="d-flex align-items-center float-left ml-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong>Loading...</strong>
              </div>
            </div>
            @if (count($guias)>0)
              <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Número</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($guias as $guia)
                    <tr>
                      <td>{{ $guia->id }}</td>
                      <td>{{ $guia->numero_guia }}</td>
                      <td>{{ $guia->descripcion }}</td>
                      <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="getGuia('{{ $guia->id }}')">Editar</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteGuia('{{ $guia->id }}')">Eliminar</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <div class="alert alert-dismissible fade show alert-danger mt-5" role="alert">
                <p>No hay guias registradas en el sistema</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
           @endif
        </div>
    </div>
    {{-- tabla guias --}}

    {{-- modal guias --}}
    <div class="modal fade" id="modal-guia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" onclick="closeMoldal('1')">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form-guia" name="form-guia">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="guia-numero" class="col-form-label">Número:</label>
                <input type="text" name="numero" id="numero" class="form-control" readonly>
              </div>
              <div class="form-group">
                @if (count($productos)>0)
                    <select name="producto" id="producto" class="form-control" required style="width: 100%">
                      <label for="guia-producto" class="col-form-label">Producto:</label>
                      <option value="">.::Seleccione::.</option>
                      @foreach ($productos as $producto)
                        <option value="{{ $producto['id'] }}">{{ $producto['nombre'] }}</option>
                      @endforeach
                    </select>
                @else
                  <div class="alert alert-dismissible fade show alert-danger mt-5" role="alert">
                    <p>para agregar una guia, es necesario que halla productos registrados en el sistema. <a href="{{ route('producto.index') }}"> Nuevo Producto</a></p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                @endif
              </div>
              <div class="form-group">
                <label for="guia-descripcion" class="col-form-label">Descripcion:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" ></textarea>
                <span id="error_desc" style="color: red; font-size:large"></span>
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
    {{-- modal guias --}}

    {{-- modal guias delete --}}
    <div class="modal fade" id="modal-guia-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminar Guía</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Desea usted, eliminar la Guia del Producto?</p>
          </div>
          <div class="modal-footer">
            <div id="deleting">
              <div class="d-flex align-items-center float-left mr-5 mt-2">
                <div class="spinner-border ml-auto" role="status"></div>
                <strong class="ml-2">Loading...</strong>
              </div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="delete">Eliminar Guía</button>
          </div>
        </div>
      </div>
    </div>
    {{-- modal guias delete --}}
@endsection

@section('script')
    <script>
      $(document).ready(function() {
        $("#sending").hide();
        $("#loading").hide();
        $("#deleting").hide();
        $("#producto").val();

          clearForm();
          $("#form-guia").submit(function( event ) {
            event.preventDefault();

            var id   = $('#id').val();
            var data = $("#form-guia").serialize();

            if (id!=0) {
              $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('guia.update') }}",
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
                        toastr.success('Guia Actualizada Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_desc').text(data.errors.descripcion).fadeIn();
                        setTimeout(function () { $('#error_desc').fadeOut(); }, 5000);
                        console.log(data.errors.descripcion);
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
                    url: "{{ route('guia.store') }}",
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
                        toastr.success('Guía Registrada Exitosamente', {timeOut: 3000});
                        closeMoldal('1');
                        setTimeout(function () { location.href = window.location.href }, 3000);
                      }else{
                        $('#error_desc').text(data.errors.descripcion).fadeIn();
                        setTimeout(function () { $('#error_desc').fadeOut(); }, 5000);
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
          $('#exampleModalLabel').text('Nueva Guía');
          $('#btn-form').text('Registrar');
        } else {
          $('#exampleModalLabel').text('Editar Guía');
          $('#btn-form').text('Actualizar');
        }
        $('#modal-guia').modal({backdrop: 'static', keyboard: false});
      }

      function closeMoldal(a) {
        if(a==1){
          $("#modal-guia").modal('hide');
        }else{
          $("#modal-guia-delete").modal('hide');
        }
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        clearForm();
      }

      function getGuia(id){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('guia') }}/"+id,
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
                $('#numero').val(data.guia.numero);
                $('#descripcion').val(data.guia.descripcion);
                $('#producto').val(data.guia.producto);
                $('#id').val(data.guia.id);
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

      function getLast(a){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('guia.last') }}",
            method: "POST",
            beforeSend: function(){
              $("#loading").fadeIn();
            },
            complete:function(data){
              $("#loading").fadeOut();
            },
            success: function(data){
              if(data.success){
                if (a==1) {
                  getModal('1');
                } else {
                  getModal('2');
                }
                $('#numero').val(data.guia);
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

      function deleteGuia(id){
        $('#modal-guia-delete').modal({backdrop: 'static', keyboard: false});
        $('#delete').click(function(){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('guia') }}/"+id,
                method: "DELETE",
                beforeSend: function(){
                  $("#deleting").fadeIn();
                },
                complete:function(data){
                  $("#deleting").fadeOut();
                },
                success: function(data){
                  if(data.success){
                    toastr.success('Guía Eliminada Exitosamente', {timeOut: 3000});
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
        $('#descripcion').val('');
        $('#id').val('0');
        $('#producto').val('');
        $('#error_desc').fadeOut();

      }

    </script>
@endsection
