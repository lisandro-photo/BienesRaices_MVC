<fieldset>
    <legend>Información General</legend>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="vendedor[nombre]" placeholder="Nombre Vendedor(a)" value="<?php echo s ($vendedor->nombre); ?>">
            
    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="vendedor[apellido]" placeholder="Apellido Vendedor(a)" value="<?php echo s ($vendedor->apellido); ?>">

</fieldset>

<fieldset>
    <legend>Información Extra</legend>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="vendedor[telefono]" placeholder="Teléfono Vendedor(a)" value="<?php echo s ($vendedor->telefono); ?>">

    <label for="email">E-Mail:</label>
    <input type="text" id="email" name="vendedor[email]" placeholder="E-Mail Vendedor(a)" value="<?php echo s ($vendedor->email); ?>">

</fieldset>