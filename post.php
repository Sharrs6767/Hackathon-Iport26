<?php
include 'dbconnect.php';

    $nome = filter_input(INPUT_POST, 'nome');
    $carga = filter_input(INPUT_POST, 'carga');
    $equipe = filter_input(INPUT_POST, 'equipe');
    $imagemnome = filter_input(INPUT_POST, 'image');
    $imagem = '../imgs/funcionarios/' . $_FILES['image']['name'];
    $imagem2 = 'imgs/funcionarios/' . $_FILES['image']['name'];

    $sql = $con->prepare("INSERT INTO funcionario (Nome,Carga,Equipe,Imagem) VALUES('$nome','$carga','$equipe','$imagem2')");
    if($sql->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'], $imagem);
        echo "<pclass ='alert alert-success text-center'>Registrado com sucesso!</p>
        <a href='postform.php'>Clique para voltar para o formulário</a>";
    }else{
        die(mysqli_error($con));
    }
?>