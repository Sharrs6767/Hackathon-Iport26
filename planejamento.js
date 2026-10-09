
// =====================================
// DADOS TEMPORÁRIOS
// =====================================

const funcionarios = funcionariosBanco; /*dados de funcionário vindos do PHP*/
 const projetos = projetosBanco; /*dados de projetos vindos do PHP*/
 const alocacoes = atividadesBanco;

let proximoIdAlocacao = 1;

// =====================================
// ELEMENTOS DA PÁGINA
// =====================================

const linhasPlanejamento =
    document.getElementById("linhasPlanejamento");

const modalFuncionario =
    document.getElementById("modalFuncionario");

const modalProjeto =
    document.getElementById("modalProjeto");

const formFuncionario =
    document.getElementById("formAdicionarFuncionario");

const formProjeto =
    document.getElementById("formAdicionarProjeto");



    
// =====================================
// ABRIR FORMULÁRIOS
// =====================================

document.getElementById("adicionarFuncionario")
    .addEventListener("click", function () {

        atualizarListaFuncionarios();
        modalFuncionario.classList.add("aberto");

    });


document.getElementById("adicionarProjeto")
    .addEventListener("click", function () {

        atualizarListaProjetos();
        atualizarFuncionariosProjeto();

        modalProjeto.classList.add("aberto");

    });


// =====================================
// CANCELAR
// =====================================

document.getElementById("cancelarFuncionario")
    .addEventListener("click", function () {

        modalFuncionario.classList.remove("aberto");

    });


document.getElementById("cancelarProjeto")
    .addEventListener("click", function () {

        modalProjeto.classList.remove("aberto");

    });



    
// =====================================
// LISTA DE FUNCIONÁRIOS
// =====================================

function atualizarListaFuncionarios() {

    const select =
        document.getElementById("selecionarFuncionario");

    select.innerHTML =
        '<option value="">Selecione um funcionário</option>';

    funcionarios.forEach(function (funcionario) {

        // Não mostrar quem já está na grade
        const existe = linhasPlanejamento.querySelector(
            `[data-funcionario-id="${funcionario.id}"]`
        );

        if (!existe) {

            const option = document.createElement("option");

            option.value = funcionario.id;
            option.textContent = funcionario.nome;

            select.appendChild(option);

        }

    });

}


// =====================================
// LISTA DE PROJETOS
// =====================================

function atualizarListaProjetos() {

    const select =
        document.getElementById("selecionarProjeto");

    select.innerHTML =
        '<option value="">Selecione um projeto</option>';

    projetos.forEach(function (projeto) {

        const option = document.createElement("option");

        option.value = projeto.id;
        option.textContent = projeto.nome;

        select.appendChild(option);

    });

}


// =====================================
// FUNCIONÁRIOS JÁ ADICIONADOS À GRADE
// =====================================

function atualizarFuncionariosProjeto() {

    const select =
        document.getElementById("funcionarioProjeto");

    select.innerHTML =
        '<option value="">Selecione um funcionário</option>';

    const linhas =
        linhasPlanejamento.querySelectorAll(".linha-funcionario");

    linhas.forEach(function (linha) {

        const id = Number(linha.dataset.funcionarioId);

        const funcionario =
            funcionarios.find(f => f.id === id);

        if (funcionario) {

            const option = document.createElement("option");

            option.value = funcionario.id;
            option.textContent = funcionario.nome;

            select.appendChild(option);

        }

    });

}

// =====================================
// CRIAR LINHA DO FUNCIONÁRIO
// =====================================

function criarLinhaFuncionario(funcionario) {
    // Impedir que o mesmo funcionário seja criado duas vezes
const linhaExistente = linhasPlanejamento.querySelector(
    `[data-funcionario-id="${funcionario.id}"]`
);

if (linhaExistente) {
    return;
}
   
    const linha = document.createElement("div");

    linha.classList.add("linha-funcionario");

    linha.dataset.funcionarioId = funcionario.id;


    // Nome do funcionário

    const nome = document.createElement("div");

    nome.classList.add("nome-funcionario");
    nome.textContent = funcionario.nome;

    linha.appendChild(nome);


    // Criar as cinco células da semana

    const dias = [
        "2026-10-05",
        "2026-10-06",
        "2026-10-07",
        "2026-10-08",
        "2026-10-09"
    ];

    dias.forEach(function (dia) {

        const celula = document.createElement("div");

        celula.classList.add("celula-dia");
        celula.dataset.dia = dia;

        linha.appendChild(celula);

    });


    // Inserir linha na grade

    linhasPlanejamento.appendChild(linha);
   const idsSalvos = JSON.parse(
    localStorage.getItem("funcionariosPlanejamento") || "[]"
);

if (!idsSalvos.includes(funcionario.id)) {
    idsSalvos.push(funcionario.id);

    localStorage.setItem(
        "funcionariosPlanejamento",
        JSON.stringify(idsSalvos)
    );
}

}


// =====================================
// ADICIONAR FUNCIONÁRIO
// =====================================

formFuncionario.addEventListener("submit", function (event) {

    event.preventDefault();

    const id = Number(
        document.getElementById("selecionarFuncionario").value
    );

    const funcionario =
        funcionarios.find(f => f.id === id);

    if (!funcionario) {
        return;
    }

    // Impedir duplicação

    const existe = linhasPlanejamento.querySelector(
        `[data-funcionario-id="${id}"]`
    );

    if (existe) {
        return;
    }

    criarLinhaFuncionario(funcionario);
    atualizarIndicadores();

    modalFuncionario.classList.remove("aberto");

    formFuncionario.reset();

});


// =====================================
// CRIAR BLOCO DE ATIVIDADE
// =====================================

function criarBlocoAtividade(alocacao) {
    // Impedir que a mesma atividade seja desenhada duas vezes
const blocoExistente = linhasPlanejamento.querySelector(
    `[data-atividade-id="${alocacao.id}"]`
);

if (blocoExistente) {
    return;
}
  
    const linha = linhasPlanejamento.querySelector(
        `[data-funcionario-id="${alocacao.funcionarioId}"]`
    );

    if (!linha) {
        return;
    }

    const celula = linha.querySelector(
        `[data-dia="${alocacao.data}"]`
    );

    if (!celula) {
        return;
    }

    const projeto =
        projetos.find(p => p.id === alocacao.projetoId);

    if (!projeto) {
        return;
    }


    // Criar bloco

    const bloco = document.createElement("div");

    bloco.classList.add("bloco-atividade");

    bloco.dataset.atividadeId = alocacao.id;
     bloco.draggable = true;

    // Nome do projeto

    const nome = document.createElement("span");

    nome.textContent = projeto.nome;


    // Quantidade de horas

    const horas = document.createElement("strong");

    horas.textContent = alocacao.horas + "h";


    bloco.appendChild(nome);
    bloco.appendChild(horas);

    celula.appendChild(bloco);

}

// =====================================
// ADICIONAR PROJETO
// =====================================

formProjeto.addEventListener("submit", async function (event) {

    event.preventDefault();
console.log("Formulário de atividade enviado!");

    const projetoId = Number(
        document.getElementById("selecionarProjeto").value
    );

    const funcionarioId = Number(
        document.getElementById("funcionarioProjeto").value
    );

    const data =
        document.getElementById("diaProjeto").value;

    const horas = Number(
        document.getElementById("horasProjeto").value
    );


    // Validação básica

    if (!projetoId || !funcionarioId || !data ||
        !Number.isInteger(horas) || horas < 1 || horas > 24) {

        return;
    }

// Enviar a atividade ao PHP para salvar no MySQL
const dados = new FormData();

dados.append("salvarAtividade", "1");
dados.append("projetoId", projetoId);
dados.append("funcionarioId", funcionarioId);
dados.append("data", data);
dados.append("horas", horas);

let resposta;

try {
    const requisicao = await fetch("planejamento.php", {
        method: "POST",
        body: dados
    });

    resposta = await requisicao.json();

    if (!requisicao.ok || !resposta.sucesso) {
        throw new Error("O PHP não conseguiu salvar a atividade.");
    }
} catch (erro) {
    console.error("Erro ao salvar atividade:", erro);
    alert("Não foi possível salvar a atividade. Tente novamente.");
    return;
}

// Criar a atividade usando o ID gerado pelo MySQL
const alocacao = {
    id: Number(resposta.id),
    projetoId: projetoId,
    funcionarioId: funcionarioId,
    data: data,
    horas: horas
};

// Guardar no array utilizado pelos cálculos
alocacoes.push(alocacao);


    // Mostrar na grade

    criarBlocoAtividade(alocacao);
      atualizarIndicadores();

    // Fechar formulário

    modalProjeto.classList.remove("aberto");

    formProjeto.reset();

});

// =====================================
// DRAG AND DROP
// =====================================

let atividadeArrastada = null;


// 1. COMEÇAR A ARRASTAR

linhasPlanejamento.addEventListener("dragstart", function (event) {

    const bloco = event.target.closest(".bloco-atividade");

    if (!bloco) {
        return;
    }

    atividadeArrastada = Number(bloco.dataset.atividadeId);

    event.dataTransfer.setData("text/plain", String(atividadeArrastada));

    event.dataTransfer.effectAllowed = "move";

    bloco.classList.add("arrastando");

});


// 2. PERMITIR SOLTAR EM UMA CÉLULA

linhasPlanejamento.addEventListener("dragover", function (event) {

    const celula = event.target.closest(".celula-dia");

    if (!celula || atividadeArrastada === null) {
        return;
    }

    event.preventDefault();

    event.dataTransfer.dropEffect = "move";

});


// 3. SOLTAR O BLOCO E SALVAR NO BANCO

linhasPlanejamento.addEventListener("drop", async function (event) {

    const celula = event.target.closest(".celula-dia");

    if (!celula || atividadeArrastada === null) {
        return;
    }

    event.preventDefault();

    const id = atividadeArrastada;
    atividadeArrastada = null;

    // Encontrar a atividade e seu bloco
    const alocacao = alocacoes.find(a => a.id === id);

    const bloco = linhasPlanejamento.querySelector(
        `[data-atividade-id="${id}"]`
    );

    if (!alocacao || !bloco) {
        return;
    }

    // Identificar o destino
    const linha = celula.closest(".linha-funcionario");
    const novoFuncionarioId = Number(linha.dataset.funcionarioId);
    const novaData = celula.dataset.dia;

    // Não salvar novamente se nada mudou
    if (alocacao.funcionarioId === novoFuncionarioId &&
        alocacao.data === novaData) {

        bloco.classList.remove("arrastando");
        return;
    }

    // Preparar os dados para o PHP
    const dados = new FormData();

    dados.append("moverAtividade", "1");
    dados.append("atividadeId", id);
    dados.append("funcionarioId", novoFuncionarioId);
    dados.append("data", novaData);

    // Impedir outro arrasto enquanto a operação é salva
    bloco.draggable = false;

    try {

        const requisicao = await fetch("planejamento.php", {
            method: "POST",
            body: dados
        });

        const resposta = await requisicao.json();

        if (!requisicao.ok || !resposta.sucesso) {
            throw new Error("Não foi possível atualizar a atividade.");
        }

        // O banco confirmou: atualizar os dados locais
        alocacao.funcionarioId = novoFuncionarioId;
        alocacao.data = novaData;

        // Mover a dragbox para a nova célula
        celula.appendChild(bloco);

        // Recalcular horas, barras e avisos
        atualizarIndicadores();

    } catch (erro) {

        console.error("Erro ao mover atividade:", erro);

        alert("Não foi possível salvar a movimentação. A atividade não foi movida.");

    } finally {

        bloco.draggable = true;
        bloco.classList.remove("arrastando");

    }

});


// 4. TERMINAR O ARRASTO

linhasPlanejamento.addEventListener("dragend", function (event) {

    const bloco = event.target.closest(".bloco-atividade");

    if (bloco) {
        bloco.classList.remove("arrastando");
    }

    atividadeArrastada = null;

});


/* =====================================
   CALCULAR HORAS POR FUNCIONÁRIO
===================================== */

function calcularHorasFuncionario(funcionarioId) {

    let total = 0;

    alocacoes.forEach(function (alocacao) {

        if (alocacao.funcionarioId === funcionarioId) {
            total += alocacao.horas;
        }

    });

    return total;
}


/* =====================================
   CALCULAR HORAS POR PROJETO
===================================== */

function calcularHorasProjeto(projetoId) {

    let total = 0;

    alocacoes.forEach(function (alocacao) {

        if (alocacao.projetoId === projetoId) {
            total += alocacao.horas;
        }

    });

    return total;
}


/* =====================================
   ATUALIZAR PAINEL DE ATIVIDADES
===================================== */

function atualizarPainelAtividades() {

    const painel = document.getElementById("resumoProjetos");

    painel.innerHTML = "";

    projetos.forEach(function (projeto) {

        const horas = calcularHorasProjeto(projeto.id);

        const item = document.createElement("div");
        item.classList.add("resumo-projeto");

        const nome = document.createElement("span");
        nome.textContent = projeto.nome;

        const resumo = document.createElement("div");
        resumo.classList.add("resumo-horas");

        const barra = document.createElement("div");
        barra.classList.add("barra-resumo");

        const progresso = document.createElement("div");
        progresso.classList.add("progresso-resumo");

        // Barra ilustrativa, usando 40h como referência
        const largura = Math.min((horas / 40) * 100, 100);
        progresso.style.width = largura + "%";

        const quantidade = document.createElement("strong");
        quantidade.textContent = horas + "h";

        barra.appendChild(progresso);

        resumo.appendChild(barra);
        resumo.appendChild(quantidade);

        item.appendChild(nome);
        item.appendChild(resumo);

        painel.appendChild(item);

    });

}


/* =====================================
   VERIFICAR SOBRECARGA SEMANAL
===================================== */

function atualizarAvisos() {

    const painel = document.getElementById("avisosPlanejamento");

    painel.innerHTML = "<h3>AVISOS DO PLANEJAMENTO</h3>";

    let encontrouSobrecarga = false;

    // Verificar apenas funcionários presentes na grade
    const linhas = linhasPlanejamento.querySelectorAll(
        ".linha-funcionario"
    );

    linhas.forEach(function (linha) {

        const id = Number(linha.dataset.funcionarioId);

        const funcionario = funcionarios.find(
            f => f.id === id
        );

        if (!funcionario) {
            return;
        }

        const horas = calcularHorasFuncionario(id);

        const percentual = funcionario.carga > 0
            ? (horas / funcionario.carga) * 100
            : 0;

        if (horas > funcionario.carga) {

            encontrouSobrecarga = true;

            const excesso = horas - funcionario.carga;

            const aviso = document.createElement("p");

            aviso.textContent =
                "ATENÇÃO! " + funcionario.nome +
                " está com " + percentual.toFixed(0) +
                "% de utilização (" + excesso +
                "h acima da carga semanal).";

            painel.appendChild(aviso);

        }

    });

    if (!encontrouSobrecarga) {

        const mensagem = document.createElement("p");

        mensagem.textContent =
            "Nenhuma sobrecarga semanal identificada.";

        painel.appendChild(mensagem);

    }

}
// =====================================
// ATUALIZAR INDICADORES DO PLANEJAMENTO
// =====================================

function atualizarIndicadores() {
    atualizarPainelAtividades();
    atualizarAvisos();
}

const funcionariosSalvos = JSON.parse(
    localStorage.getItem("funcionariosPlanejamento") || "[]"
);

funcionariosSalvos.forEach(function (id) {
    const funcionario = funcionarios.find(function (f) {
        return f.id === id;




    });

// Garantir que funcionários com atividades salvas apareçam na grade
alocacoes.forEach(function (alocacao) {

    const funcionario = funcionarios.find(
        f => f.id === alocacao.funcionarioId
    );

    if (!funcionario) {
        return;
    }

    const linhaExiste = linhasPlanejamento.querySelector(
        `[data-funcionario-id="${funcionario.id}"]`
    );

    if (!linhaExiste) {
        criarLinhaFuncionario(funcionario);
    }

});

// Reconstruir as atividades salvas no MySQL
alocacoes.forEach(function (alocacao) {
    criarBlocoAtividade(alocacao);
});







    if (funcionario) {
        criarLinhaFuncionario(funcionario);
    }
});

atualizarIndicadores();