<?php
    echo 'Crea un nuevo registro en el modelo';
    $request = CRequest::getInstance();
?>
<br />
<hr />
<form id="formPrueba" name="formPrueba" method="post" action="<?=FRAMEWORK_URL . $request->module . '/' . $request->controller?>/save">
    <input type="hidden" name="id" value="1" />
    <p>Descripcion: </p> <input type="text" name="descripcion" />
    <p>Nombre: </p> <input type="text" name="nombre"/>
    <input type="submit" />
</form>

