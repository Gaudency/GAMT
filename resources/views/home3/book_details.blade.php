<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/public">
    @include('home.css')
    <style>
        /* Gradiente para el texto general */
        .text-gradient {
            background: linear-gradient(to right, red, white);
            -webkit-background-clip: text;
            color: transparent;
        }
        /* Color rojo para el texto general */
        .text-red {
            color: red;
        }

        /* Estilo específico para las consultas */
        .text-query {
            color: white; /* Mantiene el color blanco para las consultas */
        }

        /* Gradiente para el texto del botón */
        .btn-primary {
            background-color: red; /* Color de fondo del botón */
            border: none;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: attr(data-hover); /* Texto del hover */
            position: absolute;
            left: 0;
            width: 100%;
            height: 100%;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            background: linear-gradient(to right, red, green);
            -webkit-background-clip: text;
            background-clip: text;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .btn-primary:hover::after {
            opacity: 1;
        }
    </style>
</head>

<body>

@include('home.header')

<div class="currently-market">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-heading">
                    <div class="line-dec"></div>
                    <h2 class="text-red"><em>ITE</em> ACTUALMENTE EN AMBIENTE</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="filters">
                    <ul>
                        <li data-filter="*" class="active text-gradient">Todas las Carpetas</li>
                        <li data-filter=".msc" class="text-gradient">Anteriores</li>
                        <li data-filter=".dig" class="text-gradient">Posteriores</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="">
                    <div class="item">
                        <div class="left-image">
                            <img src="book/{{$data->book_img}}" alt="" style="border-radius: 20px; min-width: 195px;">
                        </div>
                        <div class="right-content">
                            <h4 class="text-red">{{$data->title}}</h4>
                            <span class="author text-red">
                                <img src="auther/{{$data->auther_img}}" alt="" style="max-width: 50px; border-radius: 50%;">
                                <h6>UBICACION FISICA </h6>
                                <span class="text-query">{{$data->auther_name}}</span>
                            </span>
                            <div class="line-dec"></div>
                            <span class="bid text-red">
                                AUTOR <br><strong class="text-query">{{$data->auther_name}}</strong><br>
                            </span>
                            <p class="text-red"> CATEGORIA </p>
                              <span class="text-query">{{$data->category->cat_title}}</span> 
                            <p class="text-red"> DESRIPION </p>
                              <span class="text-query">{{$data->description}}</span> 
                            <p class="text-red"> AÑO </p>
                              <span class="text-query">{{$data->year}}</span> 
                            <p class="text-red"> CODIGO  </p>
                              <span class="text-query">{{$data->tomo}}</span> 
                            <p class="text-red"> PAGINAS </p>
                              <span class="text-query">{{$data->pages}}</span> 
                            <p class="text-red"> CODIGOS EXTRAS </p>
                               <span class="text-query">{{$data->isbn}}</span>
                            <p class="text-red"> FECHA </p>
                              <span class="text-query">{{ $data->created_at->format('d-m-Y') }}</span> 
                            <P class="text-red">ENVIAR SOLICITUD </P>
                            <span class="btn btn-primary" href="{{ url('borrow_books', $data->id) }}" class="btn btn-primary" data-hover="Solicitar Prestamo"> <i class="fas fa-book"></i> ENVIAR SOLICITUD </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('home.footer')

</body>

</html>


