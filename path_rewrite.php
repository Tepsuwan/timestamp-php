
<?php
$protocol = "";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $protocol = "https";
} else {
    $protocol = "http";
}

$server_root = $protocol . "://" . $_SERVER['HTTP_HOST'];
$server_root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
?>

<base href="<?php echo $server_root; ?>" />


