<table>
    <tr>
        <td style='width:200px;'>HTTP_HOST</td><td><?php echo $_SERVER['HTTP_HOST']; ?></td>
    </tr>
    <tr>
        <td style='width:200px;'>SERVER_NAME</td><td><?php echo $_SERVER['SERVER_NAME']; ?></td>
    </tr>
<?php

foreach (getallheaders() as $name => $value) {
    echo "<tr>";
    echo "<td style='width:200px;'>$name</td><td>$value</td>";
    echo "</tr>";
}
?>
</table>