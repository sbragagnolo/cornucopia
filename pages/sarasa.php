
<form method="<?php echo $this->method?>" action="<?php echo $this->action?>" >
	
	<label> Nombre </label> <?php echo $this->elements()->nombre ?> <br/>
	<label> Apellido </label> <?php echo $this->elements()->apellido ?> <br/>
	<label> Dni </label> <?php echo $this->elements()->dni ?><br/>
	<label> ID </label> <?php echo $this->elements()->id ?><br/>
	<?php echo $this->elements()->submit ?>
</form>

