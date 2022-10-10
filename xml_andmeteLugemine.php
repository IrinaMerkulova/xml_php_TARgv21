<?php
$tooted=simplexml_load_file('tooted.xml');
// andmete sisestamine
if(isSet($_POST['submit'])){

    $toodenimi=$_POST['nimetus'];
    $toodehind=$_POST['hind'];
    $toodevarv=$_POST['varv'];
    $lisa1=$_POST['lisa1'];


    $xml_tooded=$tooted->addChild('toode');
    $xml_tooded->addChild('nimetus', $toodenimi);
    $xml_tooded->addChild('hind', $toodehind);
    $xml_tooded->addChild('varv', $toodevarv);

    $lisad=$xml_tooded->addChild('lisad');
    $lisad->addChild('materjal', $lisa1);

    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->loadXML($tooted->asXML(), LIBXML_NOBLANKS);
    $xmlDoc->formatOutput=true;
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->save('tooted.xml');
    header("refresh: 0;");
}

// otsing toode nimetuse järgi
function searchByName($query){
    global $tooted;
    $result=array();
    foreach($tooted->toode as $toode){
        if(substr(strtolower($toode->nimetus), 0,
    strlen($query)) ==strtolower($query)){
        array_push($result, $toode);}
    }
    return $result;

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Andmete lugemine xml-ist</title>
</head>
<body>
<h1>Tooted .xml failist</h1>
<strong>Esimese toode nimetus xml-ist</strong>
<?php
    echo $tooted->toode[0]->nimetus;
?>
<strong>Kõik toodete andmed</strong>
<table border="1">
    <tr>
        <th>Nimetus</th>
        <th>Hind</th>
        <th>Värv</th>
        <th>Lisad: materjal</th>
        <th>Lisad: tootja</th>
    </tr>
    <?php
    foreach ($tooted->toode as $toode){
        echo "<tr>";
        echo "<td>".$toode->nimetus."</td>";
        echo "<td>".$toode->hind."</td>";
        echo "<td>".$toode->varv."</td>";
        echo "<td>".$toode->lisad->materjal."</td>";
        echo "<td>".$toode->lisad->tootja."</td>";
        echo "</tr>";
    }
    ?>
</table>
<br>
<h3>Toodete otsing</h3>
<form action="?" method="post">
    <input type="text" id="otsing" name="otsing" placeholder="toode nimetus">
    <input type="submit" value="OK">
</form>
<?php
if(!empty($_POST["otsing"])) {
    $result = searchByName($_POST["otsing"]);
    foreach ($result as $toode) {
        echo "<li>" . $toode->nimetus . ", " . $toode->hind;
        echo "</li>";
    }
}
?>
<hr>
<h2>Toote sisestamine</h2>
<table>
    <form action="" method="post" name="vorm1">
        <tr>
            <td><label for="nimetus">Toote nimetus:</label></td>
            <td><input type="text" name="nimetus" id="nimetus" autofocus></td>
        </tr>
        <tr>
            <td><label for="hind">Hind:</label></td>
            <td><input type="number" name="hind" id="hind"></td>
        </tr>
        <tr>
            <td><label for="varv">Värv:</label></td>
            <td><input type="text" name="varv" id="varv"></td>
        </tr>

        <tr>
            <td><label for="lisad">Värv:</label></td>
            <td><input type="text" name="lisa1" id="lisad"></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
            <td></td>
        </tr>
    </form>
</table>



<h1>Uudised postimees.ee</h1>
<?php
$feed=simplexml_load_file("https://www.postimees.ee/rss");
$linkide_arv=5;
$loendur=1;
foreach($feed->channel->item as $item){
    if($loendur<=$linkide_arv){

        echo "<li>";
        echo "<a href='$item->link' target='_blank'>".$item->title. "</a>";
        echo " / Autor: ".$item->author;
        echo "</li>";
    $loendur++;
    }
}


?>

</body>
</html>
<?php
