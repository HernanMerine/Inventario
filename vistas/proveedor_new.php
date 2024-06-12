<div class="container is-fluid mb-6">
	<h1 class="title">Proveedores</h1>
	<h2 class="subtitle">Nuevo proveedor</h2>
</div>
<div class="container pb-6 pt-6 pl-5 pr-5">

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/proveedor_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Contacto</label>
				  	<input class="input" type="text" name="contacto" pattern="[\w\s\d]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>