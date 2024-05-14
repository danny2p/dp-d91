<?php
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Headers and Cache Control Example</title>
</head>
<body>
<p>
<strong>
<?php

date_default_timezone_set('America/Denver');
$currentDateTime = date('m-d-Y H:i:s');
echo $currentDateTime;

?>
</p>
</strong>
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

</body>
</html>