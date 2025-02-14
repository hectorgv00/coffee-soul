<main>
	<div id="contacto">
		<div id="formulario">
			<h3>Formulario de contacto</h3>
			<p>Para hacernos llegar tu mensaje, rellena los campos que te indicamos a continuación</p>
			<input
				type="text"
				id="nombre"
				placeholder="Introduce tu nombre">
			<p id="ayudanombre" class="ayuda"></p>
			<input
				type="email"
				id="email"
				placeholder="Introduce tu email">
			<p id="ayudaemail" class="ayuda"></p>
			<input
				type="text"
				id="asunto"
				placeholder="Introduce el asunto de tu correo">
			<p id="ayudaasunto" class="ayuda"></p>
			<textarea id="mensaje">
			</textarea>
			<p id="ayudamensaje" class="ayuda"></p>
			<p>Pregunta de seguridad:</p>
			<p><small>Indica el doble del valor numérico del día del mes actual</small></p>
			<p><small>p.ej.: Si hoy es dia 13, el doble es: 26</small></p>
			<input
				type="number"
				id="dobledia"
				placeholder="Indica el doble del día de hoy">
			<p id="ayudadoble" class="ayuda"></p>
			<button id="enviar">Enviar</button>
			<p id="retroalimentacion"></p>
		</div>
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3079.693408020504!2d-0.3839184235272393!3d39.47625451234157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd604f51cf84c7ab%3A0xa07f09a4a63aad74!2sC.%20de%20Quart%2C%2012%2C%20Ciutat%20Vella%2C%2046001%20Valencia!5e0!3m2!1ses!2ses!4v1739300273065!5m2!1ses!2ses" width="600" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div>
</main>
<script>
	<?php include "contacto.js" ?>
</script>
<style>
	<?php include "contacto.css" ?>
</style>