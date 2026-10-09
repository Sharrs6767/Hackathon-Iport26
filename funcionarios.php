<?php

include 'PHP/dbconnect.php';


/* =========================================
   CADASTRAR FUNCIONÁRIO
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cadastrar"])) {

    $nome = trim($_POST["nome"]);
    $carga = (int) $_POST["carga"];
    $equipe = trim($_POST["equipe"]);

    if ($nome !== "" && $carga > 0 && $equipe !== "") {

        $sql = $con->prepare(
            "INSERT INTO funcionario (Nome, Carga, Equipe)
             VALUES (?, ?, ?)"
        );

        $sql->bind_param("sis", $nome, $carga, $equipe);

        $sql->execute();

        $sql->close();
    }

    header("Location: funcionarios.php");
    exit;
}



/* =========================================
   EXCLUIR FUNCIONÁRIO
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["excluir"])) {

    $id = (int) $_POST["id"];

    $sql = $con->prepare(
        "DELETE FROM funcionario WHERE id = ?"
    );

    $sql->bind_param("i", $id);

    $sql->execute();

    $sql->close();

    header("Location: funcionarios.php");
    exit;
}



/* =========================================
   BUSCAR FUNCIONÁRIOS
========================================= */

$result = $con->query(
    "SELECT id, Nome, Carga, Equipe FROM funcionario ORDER BY Nome"
);

$funcionarios = $result->fetch_all(MYSQLI_ASSOC);

?>






<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>iPORT - Funcionários</title>

    <!-- CSS geral do site -->
    <link rel="stylesheet" href="css/visuais.css">

    <!-- CSS específico desta página -->
    <link rel="stylesheet" href="css/funcionarios.css">

</head>


<body>


    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="sidebar" id="sidebar">

        <button class="botao-menu" id="botaoMenu">
            ☰
        </button>


        <nav class="menu">

            <a href="iport.html" class="menu-item">
                <span class="menu-texto">
                    VISÃO GERAL
                </span>
            </a>


            <a href="funcionarios.php" class="menu-item ativo">
                <span class="menu-texto">
                    FUNCIONÁRIOS (ATUAL)
                </span>
            </a>


            <a href="projetos.php" class="menu-item">
                <span class="menu-texto">
                    PROJETOS
                </span>
            </a>


            <a href="planejamento.php" class="menu-item">
                <span class="menu-texto">
                    PLANEJAMENTOS
                </span>
            </a>

            <a href="heatmap.php" class="menu-item">
                <span class="menu-texto">
                    HEATMAP
                </span>
            </a>

        </nav>


        <div class="logo">
            iPORT
        </div>

    </aside>



    <!-- =========================
         CONTEÚDO
    ========================== -->

    <main class="conteudo">


        <section class="dashboard pagina-funcionarios">


            <!-- =========================
                 TÍTULO
            ========================== -->

            <header class="titulo-pagina">

                <h1>MEUS FUNCIONÁRIOS</h1>

            </header>



            <!-- =========================
                 ÁREA PRINCIPAL
            ========================== -->

            <section class="area-funcionarios">


                <!-- Futuramente este botão abrirá
                     o formulário de funcionário -->

                <button class="botao-criar-funcionario"
        id="abrirFormulario">

    <span class="simbolo-mais">+</span>
    CRIAR FUNCIONÁRIO

</button>

               <!-- =================================
     FORMULÁRIO DE FUNCIONÁRIO
================================= -->

<div class="fundo-modal" id="fundoModal">

    <div class="modal-funcionario">

        <h2>CRIAR FUNCIONÁRIO</h2>


        <form action="funcionarios.php" method="POST">

            <label for="nome">
                NOME
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                maxlength="100"
                required
            >


            <label for="carga">
                CARGA HORÁRIA SEMANAL
            </label>

            <input
                type="number"
                id="carga"
                name="carga"
                min="1"
                max="168"
                required
            >


            <label for="equipe">
                EQUIPE
            </label>

            <input
                type="text"
                id="equipe"
                name="equipe"
                maxlength="100"
                required
            >


            <div class="botoes-formulario">

                <button
                    type="button"
                    id="cancelarFormulario"
                    class="botao-cancelar"
                >
                    CANCELAR
                </button>


                <button
                    type="submit"
                    name="cadastrar"
                    class="botao-salvar"
                >
                    SALVAR
                </button>

            </div>

        </form>

    </div>

</div>
<div class="lista-funcionarios">

    <?php foreach ($funcionarios as $funcionario): ?>

        <article class="card-funcionario">

            <h3>
                <?= htmlspecialchars($funcionario["Nome"]) ?>
            </h3>


            <p>
                <strong>Carga semanal:</strong>
          
                <?= (int) $funcionario["Carga"] ?>h
            </p>

            
            <p>
                <strong>Equipe:</strong>

                <?= htmlspecialchars($funcionario["Equipe"]) ?>
            </p>


            <!-- EXCLUIR -->

            <form
                action="funcionarios.php"
                method="POST"
                class="form-excluir"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $funcionario["id"] ?>"
                >


                <button
                    type="submit"
                    name="excluir"
                    class="botao-excluir"
                    onclick="return confirm('Deseja realmente excluir este funcionário?');"
                >
                    EXCLUIR
                </button>

            </form>

        </article>

    <?php endforeach; ?>

</div>






                <!-- =========================
                     ESTADO VAZIO
                ========================== -->

           <?php if (count($funcionarios) === 0): ?>

    <div class="estado-vazio-funcionarios">

        <div class="balao-funcionario">

            PARECE QUE VOCÊ AINDA NÃO<br>

            TEM NENHUM FUNCIONÁRIO!<br>

            PRESSIONE “CRIAR FUNCIONÁRIO”<br>

            PARA ADICIONAR UM!

        </div>


        <div class="assistente">
            IA
        </div>

    </div>

<?php endif; ?>


            </section>


        </section>


    </main>


    <!-- Mesmo JS usado pelas outras páginas -->
    <script src="js/javaiport.js"></script>
<script src="js/funcionarios.js"></script>

<!-- Code injected by live-server -->
<script type="text/javascript">
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script></body>

</html>