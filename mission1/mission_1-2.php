<?<php>/*mission_1-2 */
$filename = 'mission_1-2_YourName.txt';
$fp = fopen($filename, 'w');
fwrite($fp, 'test');
fclose($fp);
?>