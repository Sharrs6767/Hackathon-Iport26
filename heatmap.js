// ======================================
// 1. ELEMENTOS DA PÁGINA
// ======================================

const gradeHeatmap = document.getElementById("gradeHeatmap");
const areaAssistente = document.getElementById("areaAssistenteHeatmap");
const mensagemAssistente = document.getElementById("mensagemAssistente");


// ======================================
// 2. CLASSIFICAR DISPONIBILIDADE
// ======================================

function classificarDisponibilidade(percentual) {

    if (percentual > 100) {
        return {
            classe: "sobrecarga",
            texto: "Sobrecarga"
        };
    }

    if (percentual === 100) {
        return {
            classe: "maxima",
            texto: "Capacidade máxima"
        };
    }

    if (percentual >= 70) {
        return {
            classe: "alta",
            texto: "Alta utilização"
        };
    }

    return {
        classe: "disponivel",
        texto: "Disponível"
    };
}


// ======================================
// 3. CRIAR CARTÕES DOS FUNCIONÁRIOS
// ======================================

function criarCartoesHeatmap() {

    gradeHeatmap.innerHTML = "";

    if (funcionariosHeatmap.length === 0) {
        gradeHeatmap.textContent = "Nenhum funcionário cadastrado.";
        return;
    }

    funcionariosHeatmap.forEach(function (funcionario) {

        const carga = funcionario.carga;
        const horas = funcionario.horasUtilizadas;

        // Evitar divisão por zero
        const percentual = carga > 0
            ? (horas / carga) * 100
            : (horas > 0 ? 100 : 0);

        const status = carga === 0 && horas > 0
            ? { classe: "sobrecarga", texto: "Sem capacidade cadastrada" }
            : classificarDisponibilidade(percentual);

        const cartao = document.createElement("article");
        cartao.className = `cartao-heatmap ${status.classe}`;

        const nome = document.createElement("h3");
        nome.textContent = funcionario.nome;

        const porcentagem = document.createElement("strong");
        porcentagem.className = "porcentagem-heatmap";
        porcentagem.textContent = `${Math.round(percentual)}%`;

        const horasTexto = document.createElement("p");
        horasTexto.textContent = `${horas}h de ${carga}h`;

        const trilho = document.createElement("div");
        trilho.className = "trilho-heatmap";

        const barra = document.createElement("div");
        barra.className = "preenchimento-heatmap";
        barra.style.width = `${Math.min(percentual, 100)}%`;

        trilho.appendChild(barra);

        const situacao = document.createElement("p");
        situacao.className = "situacao-heatmap";

        if (carga > 0 && horas > carga) {
            situacao.textContent = `Sobrecarga de ${horas - carga}h`;
        } else {
            situacao.textContent = status.texto;
        }

        cartao.appendChild(nome);
        cartao.appendChild(porcentagem);
        cartao.appendChild(horasTexto);
        cartao.appendChild(trilho);
        cartao.appendChild(situacao);

        gradeHeatmap.appendChild(cartao);

    });

}


// ======================================
// 4. ASSISTENTE DE SOBRECARGA
// ======================================

function atualizarAssistenteHeatmap() {

    const sobrecarregados = funcionariosHeatmap.filter(function (funcionario) {
        return funcionario.horasUtilizadas > funcionario.carga;
    });

    if (sobrecarregados.length === 0) {
        areaAssistente.hidden = true;
        return;
    }

    // Escolher o funcionário com maior percentual de sobrecarga
    sobrecarregados.sort(function (a, b) {
        const percentualA = a.carga > 0
            ? a.horasUtilizadas / a.carga
            : Infinity;

        const percentualB = b.carga > 0
            ? b.horasUtilizadas / b.carga
            : Infinity;

        return percentualB - percentualA;
    });

    const funcionario = sobrecarregados[0];
    const excesso = funcionario.horasUtilizadas - funcionario.carga;

    const percentual = funcionario.carga > 0
        ? `${Math.round(
            funcionario.horasUtilizadas / funcionario.carga * 100
        )}%`
        : "acima da capacidade cadastrada";

    mensagemAssistente.textContent =
        `Atenção! ${funcionario.nome} está com ${percentual} de utilização, ` +
        `ultrapassando sua capacidade em ${excesso}h. ` +
        `Que tal redistribuir algumas atividades para outro funcionário?`;

    areaAssistente.hidden = false;
}


// ======================================
// 5. INICIALIZAR HEATMAP
// ======================================

criarCartoesHeatmap();
atualizarAssistenteHeatmap();