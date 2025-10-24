<?php
include_once '../config/config.php';

$search = mysqli_real_escape_string($con, $_POST['query']);

if ($search != '') {
    $consulta = "SELECT * FROM personal 
                 WHERE nombre LIKE '%$search%' 
                 OR DUI LIKE '%$search%'";
} else {
    $consulta = "SELECT * FROM personal";
}

$resultado = mysqli_query($con, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $i = 1;
    echo '<table class="table table-striped">
            <tr>
                <th>N°</th>
                <th>Empleado</th>
                <th>Teléfono</th>
                <th>DUI</th>
                <th>Fecha de Nacimiento</th>
                <th>Departamento</th>
                <th>Fotografía</th>
                <th>Acción</th>
            </tr>';
    while ($lista = mysqli_fetch_array($resultado)) {
        $fotografia = (!empty($lista['fotografía']) && $lista['fotografía'] != "user.png")
            ? "../img/imgEmployees/" . $lista['fotografía']
            : "../img/user.png";
        echo "<tr class='align-middle'>
                <td>{$i}</td>
                <td>{$lista['nombre']}</td>
                <td>{$lista['Telefono']}</td>
                <td>{$lista['DUI']}</td>
                <td>{$lista['fechaNacimiento']}</td>
                <td>{$lista['departamento']}</td>
                <td><img src='{$fotografia}' style='width:40px; height:40px; border-radius:50%;'></td>
                <td>
                    <a href='../employees/editEmployee.php?idPersonal={$lista['idPersonal']}' class='btn btn-primary btn-sm'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <form action='../employees/deleteEmployee.php' method='POST' class='d-inline' onsubmit=\"return confirm('¿Eliminar este usuario?');\">
                        <input type='hidden' name='idPersonal' value='{$lista['idPersonal']}'>
                        <button class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>
                    </form>
                </td>
            </tr>";
        $i++;
    }
    echo '</table>';
} else {
    echo "<div class='alert alert-warning text-center'>No se encontró ningún registro.</div>";
}

mysqli_close($con);
?>