<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row align-items-start">
        <div class="col">
            <form method="post" action="post.php" class="bg-secondary p-5 my-5 text-light" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" placeholder="insira nome" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="carga">Carga Horária</label>
                    <input type="text" name="carga" placeholder="insira apenas números" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="equipe">Equipe</label>
                    <input type="text" name="equipe" placeholder="insira equipe" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="image">Imagem</label>
                    <input class="form-control" type="file" name="image" id="image" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>    
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>