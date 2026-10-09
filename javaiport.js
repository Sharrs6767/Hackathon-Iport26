const botaoMenu = document.getElementById("botaoMenu");
const sidebar = document.getElementById("sidebar");
const conteudo = document.querySelector(".conteudo");


botaoMenu.addEventListener("click", function () {

    sidebar.classList.toggle("recolhida");

    conteudo.classList.toggle("expandido");

});
/*JAVA ABAS LATERAIS */