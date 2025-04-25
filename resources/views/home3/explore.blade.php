<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSACADOR USER</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Efecto hover para imágenes */
        .card img:hover {
            transform: scale(1.1); /* Aumenta el tamaño de la imagen */
            transition: transform 0.3s ease-in-out; /* Transición suave */
            z-index: 2; /* Sobresalir del contenido cercano */
            border: 2px solid #fff; /* Añadir borde blanco */
        }
        /* Hover para el botón "Ver más" */
        .card .btn-primary:hover {
            transform: scale(1.1); /* Aumenta el tamaño del botón */
            transition: transform 0.3s ease-in-out; /* Transición suave */
            background-color: #1d4ed8; /* Cambia color de fondo */
            color: white; /* Cambia color de texto */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efecto de sombra */
        }
        /* Gradiente para los botones */
        .btn-primary {
            background: linear-gradient(to right, red, white);
            border: none;
        }

        /* Gradiente para los botones de las categorías */
        .nav-link.active, .nav-link {
            background: linear-gradient(to right, red, green);
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
            margin: 5px;
        }

        .nav-link:hover {
            background: linear-gradient(to right, red, green);
            color: white;
        }
    </style>
</head>
<body class="bg-black text-light">
    <div class="container py-4">
        <h1 class="text-center display-4 text-white">BUSACAR CARPETAS</h1>
        <!-- Formulario de búsqueda -->
        <form action="{{ url('search') }}" method="GET" >
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por título, año, descripción o tomo" value="{{ request()->input('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        
        <!-- Categorías -->
        <ul class="nav justify-content-center mb-4">
            <li class="nav-item">
                <a href="{{ url('explore') }}" class="nav-link active">Todas las Carpetas</a>
            </li>
            @foreach($category as $category)
                <li class="nav-item">
                    <a href="{{ url('cat_search', $category->id) }}" class="nav-link">{{ $category->cat_title }}</a>
                </li>
            @endforeach
        </ul>
        
        <!-- Resultados -->
        <div class="row">
            @foreach($data as $book)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card bg-secondary">
                        <!-- Imagen -->
                        <img src="{{ asset('book/' . $book->book_img) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                    </div>
                    <a href="{{ url('book_details', $book->id) }}" class="btn btn-primary"> <i class="fas fa-info-circle"></i> Ver Detalles de Carpeta </a>
                    <a href="{{ url('borrow_books', $book->id) }}" class="btn btn-primary"> <i class="fas fa-book"></i> Solicitar Prestamo </a>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


