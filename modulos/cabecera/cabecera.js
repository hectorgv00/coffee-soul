/*
    Este archivo procesa el poblado de elementos y subelementos en la cabecera de la pagina
*/

async function procesaCabecera() {
  try {
    // Cargo los menús de la cabecera ///////////////////////////////////
    const response = await fetch("../back/?tabla=categorias"); // Cargo un endpoint en el back
    const datos = await response.json(); // La convierto en json
    console.log(datos);

    const cabecera = document.querySelector("header nav ul"); // Selecciono la cabecera
    const plantilla = document.querySelector("#elementomenu");

    // Ordenamos por el indice para que salgan bien en la cabecera
    const sorted = datos.sort((a, b) => a.indice - b.indice);

    sorted.forEach((dato) => {
      console.log(sorted);
      // Para cada dato recibido
      const instancia = plantilla.content.cloneNode(true); // Creo una instancia
      const enlace = instancia.querySelector("a"); // Selecciono el enlace interior

      enlace.textContent = dato.nombre; // Le pongo el atributo de texto
      enlace.setAttribute("href", `categoria.php?cat=${dato.Identificador}`); // Y le digo a qué página debe ir
      enlace.setAttribute("cat", dato.Identificador); // Le pongo un atributo categoría
      instancia.querySelector("li").classList.add("categoria");

      enlace.addEventListener("mouseover", async function () {
        // Cuando pase por encima de esa categoria
        const tituloseccion = this.textContent; // Cargo el titulo de la categoria
        const response = await fetch(
          `../back/?busca=productos&campo=categorias_nombre&dato=${this.getAttribute(
            "cat"
          )}`
        ); // Fetch para obtener productos por cateogrias
        const datos = await response.json(); // La convierto en json
        console.log(datos);

        document.querySelector("#categoria").textContent = tituloseccion; // Pongo el titulo de la categoria
        document.querySelector("#productos").innerHTML = ""; // Vacio los productos
        datos.forEach((dato) => {
          // Para cada uno de los productos
          document.querySelector(
            "#productos"
          ).innerHTML += `<li><a href='producto.php?prod=${dato.Identificador}'>${dato.titulo}</a></li>`; // Los pongo en el listado
        });
        const cabecera = document.querySelector("header");
        difumina(cabecera);
      });

      cabecera.prepend(instancia);
    });
  } catch (error) {
    console.warn("Error al cargar las categorías:", error);
    document.querySelector("#contienemodal").style.display = "block";
  }

  // Aplico difuminado en el fondo al entrar y salir de la cabecera /////////////////////

  const cabecera = document.querySelector("header"); // Selecciono la cabecera
  const categorias = document.querySelectorAll(".categoria");

  /*cabecera.addEventListener("mouseenter",function(){
			  difumina(cabecera)
		  })*/

  cabecera.onmouseleave = () => {
    // Cuando salgo
    document.querySelector("main").classList.remove("difuminado"); // Le quito la clase css
    document.querySelector("header").classList.remove("grande");
    cabecera.style.background = "rgba(255,255,255,1)"; // Y le pongo un color blanco sólido
  };
}

procesaCabecera();
