<?php
include 'PHP/dbconnect.php';

// Consultar funcionários e suas horas na semana do planejamento
$sql = "
    SELECT
        f.id,
        f.Nome,
        f.Carga,
        COALESCE(SUM(p.horas), 0) AS horasUtilizadas
    FROM funcionario f
    LEFT JOIN periodo p
        ON p.funcionarioid = f.id
        AND p.diasemana BETWEEN '2026-10-05' AND '2026-10-09'
    GROUP BY f.id, f.Nome, f.Carga
    ORDER BY f.Nome
";

$resultado = $con->query($sql);
$funcionariosHeatmap = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>iPORT - Heatmap</title>

    <link rel="stylesheet" href="css/visuais.css">
    <link rel="stylesheet" href="css/heatmap.css">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <button class="botao-menu" id="botaoMenu">☰</button>

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

            <a href="planejamento.php" class="menu-item">
                <span class="menu-texto">PLANEJAMENTOS </span>
            </a>

            <a href="heatmap.php" class="menu-item ativo">
                <span class="menu-texto">HEATMAP(ATUAL)</span>
            </a>
        </nav>

        <div class="logo">iPORT</div>
    </aside>

    <!-- CONTEÚDO -->
    <main class="conteudo">
        <section class="dashboard pagina-heatmap">

            <header class="titulo-heatmap">
                <h1>HISTÓRICO DE DISPONIBILIDADE</h1>
                <span>05/10 - 09/10</span>
            </header>

            <div class="estrutura-heatmap">

                <!-- Cartões criados pelo JavaScript -->
                <section class="grade-heatmap" id="gradeHeatmap">
                </section>

                <!-- Legenda das cores -->
                <aside class="legenda-heatmap">
                    <h2>DISPONIBILIDADE</h2>

                    <p><span class="ponto-status disponivel"></span> Disponível</p>
                    <p><span class="ponto-status alta"></span> Alta utilização</p>
                    <p><span class="ponto-status maxima"></span> Capacidade máxima</p>
                    <p><span class="ponto-status sobrecarga"></span> Sobrecarga</p>
                </aside>

            </div>

            <!-- Assistente: só aparece quando houver sobrecarga -->
            <section class="area-assistente-heatmap" id="areaAssistenteHeatmap" hidden>
                <div class="balao-heatmap" id="mensagemAssistente"></div>

                <div class="imagem-assistente-heatmap">
                    <!-- Futuramente colocaremos aqui a arte do Krita -->
                    <span>iPORT</span>
                </div>
            </section>

        </section>
    </main>

    <!-- PHP transforma os registros em dados para o JavaScript -->
    <script>
        const funcionariosHeatmap = <?= json_encode(
            array_map(function ($funcionario) {
                return [
                    "id" => (int) $funcionario["id"],
                    "nome" => $funcionario["Nome"],
                    "carga" => (int) $funcionario["Carga"],
                    "horasUtilizadas" => (int) $funcionario["horasUtilizadas"]
                ];
            }, $funcionariosHeatmap),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        ) ?>;
    </script>

    <script src="js/javaiport.js"></script>
    <script src="js/heatmap.js"></script>

</body>
</html>