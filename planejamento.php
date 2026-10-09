<?php

include 'PHP/dbconnect.php';
// Atualizar a posição de uma atividade arrastada
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["moverAtividade"])) {

    $atividadeId = (int) ($_POST["atividadeId"] ?? 0);
    $funcionarioId = (int) ($_POST["funcionarioId"] ?? 0);
    $data = $_POST["data"] ?? "";

    $diasValidos = [
        "2026-10-05", "2026-10-06", "2026-10-07",
        "2026-10-08", "2026-10-09"
    ];

    $sucesso = false;

    if ($atividadeId > 0 && $funcionarioId > 0 &&
        in_array($data, $diasValidos, true)) {

        $sql = $con->prepare(
            "UPDATE periodo
             SET funcionarioid = ?, diasemana = ?
             WHERE periodoid = ?
             AND EXISTS (
                 SELECT 1 FROM funcionario
                 WHERE id = ?
             )"
        );

        $sql->bind_param(
            "isii",
            $funcionarioId,
            $data,
            $atividadeId,
            $funcionarioId
        );

        $sucesso = $sql->execute() && $sql->affected_rows === 1;

        $sql->close();
    }

    header("Content-Type: application/json");

    if (!$sucesso) {
        http_response_code(400);
    }

    echo json_encode(["sucesso" => $sucesso]);
    exit;
}
// Salvar uma nova atividade enviada pelo JavaScript
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["salvarAtividade"])) {

    $funcionarioid = (int) ($_POST["funcionarioId"] ?? 0);
    $projetoid = (int) ($_POST["projetoId"] ?? 0);
    $horas = (int) ($_POST["horas"] ?? 0);
    $diasemana = $_POST["data"] ?? "";

    if ($funcionarioid > 0 && $projetoid > 0 &&
        $horas >= 1 && $horas <= 24 &&
        in_array($diasemana, [
            "2026-10-05", "2026-10-06", "2026-10-07",
            "2026-10-08", "2026-10-09"
        ], true)) {

        $sql = $con->prepare(
            "INSERT INTO periodo (funcionarioid, projetoid, horas, diasemana)
             VALUES (?, ?, ?, ?)"
        );

        $sql->bind_param("iiis", $funcionarioid, $projetoid, $horas, $diasemana);

        if ($sql->execute()) {
            $id = $con->insert_id;

            header("Content-Type: application/json");
            echo json_encode(["sucesso" => true, "id" => $id]);

            $sql->close();
            exit;
        }

        $sql->close();
    }

    header("Content-Type: application/json");
    http_response_code(400);
    echo json_encode(["sucesso" => false]);
    exit;
}

$result = $con->query( 
    "SELECT id, Nome, Carga FROM funcionario ORDER BY Nome"  
);

$funcionarios = $result->fetch_all(MYSQLI_ASSOC);

$resultProjetos = $con->query(
    "SELECT id, nome FROM projeto ORDER BY nome"
);

$projetos = $resultProjetos->fetch_all(MYSQLI_ASSOC);
// Buscar atividades salvas para a semana exibida
$resultAtividades = $con->query(
    "SELECT p.periodoid, p.funcionarioid, p.projetoid,
            p.horas, p.diasemana
     FROM periodo p
     INNER JOIN funcionario f ON f.id = p.funcionarioid
     INNER JOIN projeto pr ON pr.id = p.projetoid
     WHERE p.diasemana BETWEEN '2026-10-05' AND '2026-10-09'"
);

$atividadesBanco = $resultAtividades->fetch_all(MYSQLI_ASSOC);





?>






<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>iPORT - Planejamentos</title>

    <!-- CSS geral -->
    <link rel="stylesheet" href="css/visuais.css">

    <!-- CSS exclusivo -->
    <link rel="stylesheet" href="css/planejamento.css">
</head>

<body>

    <!-- SIDEBAR -->

    <aside class="sidebar" id="sidebar">

        <button class="botao-menu" id="botaoMenu">
            ☰
        </button>

        <nav class="menu">

            <a href="iport.html" class="menu-item">
                <span class="menu-texto">VISÃO GERAL</span>
            </a>

            <a href="funcionarios.php" class="menu-item">
                <span class="menu-texto">FUNCIONÁRIOS</span>
            </a>

            <a href="projetos.php" class="menu-item">
                <span class="menu-texto">PROJETOS</span>
            </a>

            <a href="planejamento.php" class="menu-item ativo">
                <span class="menu-texto">PLANEJAMENTOS (ATUAL)</span>
            </a>

            <a href="heatmap.php" class="menu-item">
                <span class="menu-texto">HEATMAP</span>
            </a>

        </nav>

        <div class="logo">iPORT</div>

    </aside>


    <!-- CONTEÚDO PRINCIPAL -->

    <main class="conteudo">

        <section class="dashboard pagina-planejamento">

            <!-- TÍTULO -->

            <header class="titulo-planejamento">
                <h1>PLANEJAMENTO SEMANAL</h1>
                <span>05/10 - 09/10</span>
            </header>


            <!-- ÁREA DE TRABALHO -->

            <div class="estrutura-planejamento">

                <section class="area-planejamento">

                    <!-- GRADE SEMANAL -->

                    <div class="grade-rolagem">

                        <div class="grade-planejamento">

                            <!-- CABEÇALHO -->

                            <div class="cabecalho-grade">

                                <div class="coluna-funcionario">
                                    FUNCIONÁRIO
                                </div>

                                <div class="coluna-dia">SEG</div>
                                <div class="coluna-dia">TER</div>
                                <div class="coluna-dia">QUA</div>
                                <div class="coluna-dia">QUI</div>
                                <div class="coluna-dia">SEX</div>

                            </div>


                            <!--
                                O JavaScript criará aqui
                                uma linha para cada funcionário.
                            -->

                         <div class="linhas-planejamento" id="linhasPlanejamento"> <!-- Não temos mais a Maria fictícia, então o 
                            JavaScript vai preencher essa área com os
                            funcionários do banco de dados MySQL-->
                                </div>
       


                            </div>

                        </div>

                    


                    <!-- CONTROLES INFERIORES -->

                    <div class="rodape-planejamento">

                       <div class="botoes-planejamento">

    <button
        type="button"
        id="adicionarFuncionario"
        class="botao-planejamento">
        + ADICIONAR FUNCIONÁRIO
    </button>

    <button
        type="button"
        id="adicionarProjeto"
        class="botao-planejamento">
        + ADICIONAR PROJETO
    </button>

</div>


                        <!-- FUTUROS ALERTAS -->

                        <div class="avisos-planejamento"
                             id="avisosPlanejamento">

                            <h3>AVISOS DO PLANEJAMENTO</h3>

                            <p>
                                Nenhum conflito identificado
                                no exemplo atual.
                            </p>

                        </div>

                    </div>

                </section>


                <!-- PAINEL DIREITO -->

                <aside class="painel-projetos-planejamento">

                    <h2>ATIVIDADES</h2>

                    <div class="lista-resumo-projetos"
                         id="resumoProjetos">
                    </div>

                </aside>

            </div>

        </section>

    </main>



<!-- =====================================
     FORMULÁRIO ADICIONAR FUNCIONÁRIO
===================================== -->

<div class="fundo-modal-planejamento" id="modalFuncionario">

    <div class="modal-planejamento">

        <h2>ADICIONAR FUNCIONÁRIO</h2>

        <form id="formAdicionarFuncionario">

            <label for="selecionarFuncionario">
                FUNCIONÁRIO
            </label>

            <select id="selecionarFuncionario" required>
                <option value="">Selecione um funcionário</option>
            </select>

            <div class="botoes-modal-planejamento">

                <button type="button"
                        class="botao-cancelar-modal"
                        id="cancelarFuncionario">
                    CANCELAR
                </button>

                <button type="submit"
                        class="botao-confirmar-modal">
                    ADICIONAR
                </button>

            </div>

        </form>

    </div>

</div>


<!-- =====================================
     FORMULÁRIO ADICIONAR PROJETO
===================================== -->

<div class="fundo-modal-planejamento" id="modalProjeto">

    <div class="modal-planejamento">

        <h2>ADICIONAR ATIVIDADE</h2>

       <form id="formAdicionarProjeto">

            <label for="selecionarProjeto">
                PROJETO
            </label>

            <select id="selecionarProjeto" required>
                <option value="">Selecione um projeto</option>
            </select>


            <label for="funcionarioProjeto">
                FUNCIONÁRIO RESPONSÁVEL
            </label>

            <select id="funcionarioProjeto" required>
                <option value="">Selecione um funcionário</option>
            </select>


            <label for="diaProjeto">
                DIA DA SEMANA
            </label>

            <select id="diaProjeto" required>

                <option value="">Selecione o dia</option>
                <option value="2026-10-05">SEGUNDA</option>
                <option value="2026-10-06">TERÇA</option>
                <option value="2026-10-07">QUARTA</option>
                <option value="2026-10-08">QUINTA</option>
                <option value="2026-10-09">SEXTA</option>

            </select>


            <label for="horasProjeto">
                QUANTIDADE DE HORAS
            </label>

            <input type="number"
                   id="horasProjeto"
                   min="1"
                   max="24"
                   required>


            <div class="botoes-modal-planejamento">

                <button type="button"
                        class="botao-cancelar-modal"
                        id="cancelarProjeto">
                    CANCELAR
                </button>

                <button type="submit"
                        class="botao-confirmar-modal"
                        name="projetocadastrar">
                    ADICIONAR
                </button>

            </div>
                

        </form>

    </div>

</div>



<!------------------------------------------------
     Para passar funcionários e projetos para JavaScript é necessário converter os dados para JSON -->

<script>
    const funcionariosBanco = <?= json_encode(  
        array_map(function ($funcionario) {
            return [
                "id" => (int) $funcionario["id"],
                "nome" => $funcionario["Nome"],
                "carga" => (int) $funcionario["Carga"]
            ];
        }, $funcionarios),
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?>;


const projetosBanco = <?= json_encode(
    array_map(function ($projeto) {
        return [
            "id" => (int) $projeto["id"],
            "nome" => $projeto["nome"]
        ];
    }, $projetos),
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;


const atividadesBanco = <?= json_encode(
    array_map(function ($atividade) {
        return [
            "id" => (int) $atividade["periodoid"],
            "funcionarioId" => (int) $atividade["funcionarioid"],
            "projetoId" => (int) $atividade["projetoid"],
            "horas" => (int) $atividade["horas"],
            "data" => $atividade["diasemana"]
        ];
    }, $atividadesBanco),
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;


</script>

<!-- AQUI, É ISSO, isso aqui resolve nosso problema de passar os dados do PHP para o JavaScript, 
mas ainda precisamos criar a lógica para preencher
 a grade de planejamento com esses dados. AQUI NO SCRIPTS TBM ESTÁ A FONTE DAS 3 INFORMAÇÕES PROJETO, FUNCIONARIO E ATIVIDADES -->








    <script src="js/javaiport.js"></script>
    <script src="js/planejamento.js"></script>

</body>
</html>
