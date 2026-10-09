// ======================================
// ELEMENTOS DA PÁGINA
// ======================================

const abrirFormularioEquipe =
    document.getElementById("abrirFormularioEquipe");

const fundoModalEquipe =
    document.getElementById("fundoModalEquipe");

const cancelarEquipe =
    document.getElementById("cancelarEquipe");

const formEquipe =
    document.getElementById("formEquipe");

const listaEquipes =
    document.querySelector(".lista-equipes");

const estadoVazio =
    document.querySelector(".estado-vazio-equipes");


// ======================================
// ABRIR FORMULÁRIO
// ======================================

abrirFormularioEquipe.addEventListener("click", function () {

    fundoModalEquipe.classList.add("aberto");

});


// ======================================
// CANCELAR
// ======================================

cancelarEquipe.addEventListener("click", function () {

    fundoModalEquipe.classList.remove("aberto");

});


// ======================================
// CRIAR EQUIPE
// ======================================

formEquipe.addEventListener("submit", function (event) {

    // Impede o formulário de recarregar a página
    event.preventDefault();


    const nome =
        document.getElementById("nomeEquipe").value;

    const descricao =
        document.getElementById("descricaoEquipe").value;


    // Busca funcionários marcados

    const funcionariosSelecionados =
        document.querySelectorAll(
            '.selecao-funcionarios input[type="checkbox"]:checked'
        );


    // Cria array com nomes

    const nomesFuncionarios = [];

    funcionariosSelecionados.forEach(function (funcionario) {

        nomesFuncionarios.push(funcionario.value);

    });


    // Cria o card

    const card = document.createElement("article");

    card.classList.add("card-equipe");


    card.innerHTML = `
        <h3>${nome}</h3>

        <p>${descricao}</p>
    `;


    // Coloca na página

    listaEquipes.appendChild(card);


    // Esconde mensagem de página vazia

    estadoVazio.style.display = "none";


    // Fecha formulário

    fundoModalEquipe.classList.remove("aberto");


    // Limpa formulário

    formEquipe.reset();

});