<!DOCTYPE html>
<html>
  <head> 
   @include('admin.css')
   <!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Tus estilos personalizados -->
<link href="/path/to/admin.css" rel="stylesheet">

   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <style type="text/css">
     
    .table_center
    {
      text-align: center;
      margin: auto;
      border: 1px solid yellowgreen;
      margin-top: 50px;
    }

    th
    {
      background-color: transparent;
      padding: 10px;
      font-size: 20px;
      font-weight: bold;
      color: white;
    }
    .btn-warning {
    background-color: orange !important; 
    border: none !important;            
    color: black !important;   
}

.btn-info {
  background-color: lightblue !important;
    border: none !important;
    color: black !important;
}  

   </style>
  </head>
  <body>
    @include('admin.header')
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <!-- Sidebar Navigation end-->
      
       <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
          <div style="margin-bottom: 20px;">
            <a href="{{ url('add_document') }}" class="btn btn-success">Crear Nuevo </a>
              </div>

            @if(session()->has('message'))

                <div class="alert alert-success">

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                  
                  {{session()->get('message')}}



                </div>
                

                @endif

            <div>
              
              <table class="table_center">
                
                <tr>
                  <th>N_codigo</th>
                  <th>nombre of solicitante</th>
                  <th>N_hojas</th>
                  <th>N_de_Carpeta</th>
                  <th>description</th>
                  <th>Category</th>
                  <th>Estado</th>
                  <th>Cambiar estado</th>
                  <th>Delete</th>
                  <th>Update</th>
                </tr>

                @foreach($documents as $document)

                <tr>
                  <td>{{$document->N_codigo}}</td>
                  <td>{{$document->applicant_name}}</td>
                  <td>{{$document->N_hojas}}</td>
                  <td>{{$document->N_carpeta}}</td>
                  <td>{{$document->descrition}}</td>
                  <td>{{$document->category->cat_title ?? 'Sin categoría' }}</td>
                  <td>{{$document->status}}</td>  
                <th>
                  <form action="{{ url('change_status', $document->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="prestado">
                    <button type="submit" class="btn btn-warning">Prestado</button>
                </form>
                <form action="{{ url('change_status', $document->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="devuelto">
                    <button type="submit" class="btn btn-info">Devuelto</button>
                </form>
              </td>
                  <td>
                    <a onclick="confirmation(event)" href="{{url('document_delete',$document->id)}}" class="btn btn-danger">Eliminar</a>
                  </td>

                  <td>
                    <a href="{{url('edit_document',$document->id)}}" class="btn btn-info">Actualizar</a>
                  </td>
                </tr>
                  @endforeach

              </table>

            </div>
      </div>
        </div>
          </div>
       

       @include('admin.footer')

       <script type="text/javascript">
         
          function confirmation(ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href');  
        console.log(urlToRedirect); 
       
      swal({
            title: "Are you sure to Delete this",
            text: "You will not be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

        .then((willCancel) => {
            if (willCancel) {
       
                window.location.href = urlToRedirect;
               
            }  


        });

        
    }
       </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome para íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>