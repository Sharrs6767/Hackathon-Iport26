const abrirFormulario = document.getElementById("abrirFormulario");

const cancelarFormulario = document.getElementById("cancelarFormulario");

const fundoModal = document.getElementById("fundoModal");


abrirFormulario.addEventListener("click", function () {

    fundoModal.classList.add("aberto");

});


cancelarFormulario.addEventListener("click", function () {

    fundoModal.classList.remove("aberto");

});

