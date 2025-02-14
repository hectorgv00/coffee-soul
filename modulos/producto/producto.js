console.log("js ok");

let botones = document.querySelectorAll(".controlador button");
botones.forEach(function (boton) {
  boton.onclick = function () {
    seleccionaFoto(boton.value);
  };
});

function seleccionaFoto(boton) {
  let valor = boton;
  document.querySelector(".contenedorpasafotos").style.left =
    (0 - (valor - 1)) * 800 + "px";
}
