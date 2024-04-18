<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<table>
    <tr>
        <td style='width:200px;'>HTTP_HOST</td><td><?php echo $_SERVER['HTTP_HOST']; ?></td>
    </tr>
    <tr>
        <td style='width:200px;'>SERVER_NAME</td><td><?php echo $_SERVER['SERVER_NAME']; ?></td>
    </tr>
<?php

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
        }
}

foreach (getallheaders() as $name => $value) {
    echo "<tr>";
    echo "<td style='width:200px;'>$name</td><td>$value</td>";
    echo "</tr>";
}
?>
</table>