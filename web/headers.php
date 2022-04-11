<table>
<?php

foreach (getallheaders() as $name => $value) {
    echo "<tr>";
    echo "<td style='width:200px;'>$name</td><td>$value</td>";
    echo "</tr>";
}
?>
</table>