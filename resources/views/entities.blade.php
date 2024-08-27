<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracción de Entidades</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Extracción de Entidades desde una URL</h1>
        <form id="entityForm">
            <div class="mb-3">
                <label for="url" class="form-label">Ingresa la URL:</label>
                <input type="url" class="form-control" id="url" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <hr>
        <h2>Entidades Encontradas</h2>
        <table class="table table-striped" id="entityTable">
            <thead>
                <tr>
                    <th>Entidad</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $('#entityForm').on('submit', function(event) {
            event.preventDefault();
            let url = $('#url').val();
            $.ajax({
                url: '/extract-entities',
                method: 'POST',
                data: {
                    url: url,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    let tableBody = $('#entityTable tbody');
                    tableBody.empty();
                    response.forEach(function(entity) {
                        tableBody.append('<tr><td>' + entity.name + '</td><td>' + entity.type + '</td></tr>');
                    });
                },
                error: function(xhr) {
                    let errorMessage = $('#errorMessage');
                    errorMessage.removeClass('d-none'); // Mostrar el div de error
                    errorMessage.html('<strong>Error:</strong> ' + xhr.responseText); // Mostrar el JSON de error

                    console.error("Error: ", xhr.responseText); // También lo mostramos en la consola del navegador
                }
            });
        });
    </script>
</body>

</html>