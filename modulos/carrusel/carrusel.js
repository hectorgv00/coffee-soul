let puntos = document.querySelectorAll(".punto")					// Selecciono los puntos
puntos.forEach(function(punto,index){									// PAra cada uno de los puntos
	punto.onclick = function(){											// Cuando haga click en un punto			
		let carrusel1 = document.querySelector("#carrusel1")		// Cojo el carrusel
		carrusel1.classList.remove("animado1")							// Paro la animacion
		carrusel1.style.left = 0-index*1024+"px"						// Le pongo a mano el desfase en base al punto en el cual he hecho click
	}
})
