<table>
<?php

foreach (getallheaders() as $name => $value) {
    echo "<tr>";
    echo "<td>$name</td><td>$value</td>";
    echo "</tr>";
}
?>
</table>