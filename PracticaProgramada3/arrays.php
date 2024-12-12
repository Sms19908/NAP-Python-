<?php
$transactions = array(
    1 => "Compra Supermercado",
    2 => "Entradas de cine",
    3 => "Parqueo Mall",
    4 => "Veterinario",
    5 => "Comida en Restaurante mesa para 3 personas"

);
$prices = array(
    3000,
    1500,
    2500,
    80000,
    20000
);
//Arrays
$totalC = 0;
$cashback = 0;
$totalI = 0;
$totalF = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // llamado de los datos por metodo POST
    $transactionId = $_POST['transactionId'];
    $description = $_POST['description'];
    $value = $_POST['value'];

    // Push de los datos al array previamente creado
    function addData($description)
    {
        global $transactions;
        array_push($transactions, $description);
    }

    function addPrice($value)
    {
        global $prices;
        array_push($prices, $value);
    }
    addData($description);
    addPrice($value);

    // Prueba para ver que los arrays agregan datos
    $temp = array_combine($transactions, $prices);
    //print_r($temp);

    generarEstadoDeCuenta();
}

function generarEstadoDeCuenta()
{
    global $transactions;
    global $prices;
    global $temp;
    global $totalC;
    global $cashback;
    global $totalI;
    global $totalF;


    $totalC = array_sum($prices);

    $cashback = $totalC * 0.001;

    $totalI = $totalC + ($totalC * 0.026);

    $totalF = $totalI - $cashback;

    generarTxt($transactions, $prices, $totalC, $totalF, $totalI);

}

function generarTxt($temp, $totalC, $totalF, $totalI, $cashback)
{
    $archivo = fopen("estado_cuenta.txt", "w") or die("NO se puede abrir el archivo");
    foreach ($temp as $transaction) {
        $count=1;
        $temp = " " . $transaction ." ";
        fwrite($archivo, $count . $temp);
        $count++;
    }
    $txt = "Monto total de contado: " . $totalC;
    fwrite($archivo, $txt);
    $txt = "Monto total con impuestos" . $totalI;
    fwrite($archivo, $txt);
    $txt = "Monto de cashback conseguido: " . $cashback;
    fwrite($archivo, $txt);
    $txt = "Monto final a cancelar: " . $totalF;
    fwrite($archivo, $txt);
    fclose($archivo);

    // leer un archivo:
    $archivoALeer = fopen(filename: "estado_cuenta.txt", mode: "r") or die("No se puede abrir el archivo");
    while (!feof($archivoALeer)) {
        echo fgets($archivoALeer);
    }
    fclose($archivoALeer);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Display</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body style="background-color: #17153B;">
    <section>
        <div class="container d-flex justify-content-center min-h-100 p-5">
            <div class="card p-4 shadow-lg w-100" style="max-width: 520px; background-color: #C8ACD6; color: #17153B;">
                <h3 class="card-title text-center mb-4">Transacciones Realizadas</h3>
                <ol>
                    <?php
                    foreach ($temp as $transaction => $price) {
                        echo "<li> $transaction = $price ";
                    }
                    ?>
                </ol>
                <button class="btn btn-primary ml-2" style="background: #2E236C; border: #2E236C; color: #FBFBFB;"
                    onclick="window.location.href='index.html'">Agregar mas transacciones</button>
            </div>
        </div>
    </section>
    <section>
        <div class="container d-flex flex-row">
            <div class="card p-4 shadow-lg w-100" style="max-width: 400px; background-color: #433D8B; color: #FBFBFB;">
                <h3 class="card-title text-center mb-4">Monto total de contado</h3>
                <h4>₡
                    <?php echo "$totalC"
                    ?>
                </h4>
            </div>

            <div class="card p-4 shadow-lg w-100" style="max-width: 400px; background-color: #433D8B; color: #FBFBFB;">
                <h3 class="card-title text-center mb-4">Monto total con intereses </h3>
            </div>

            <div class="card p-4 shadow-lg w-100" style="max-width: 400px; background-color: #433D8B; color: #FBFBFB;">
                <h3 class="card-title text-center mb-4">Monto de cashback</h3>
            </div>

            <div class="card p-4 shadow-lg w-100" style="max-width: 400px; background-color: #433D8B; color: #FBFBFB;">
                <h3 class="card-title text-center mb-4">Monto total a cancelar</h3>
            </div>
        </div>
    </section>
</body>

</html>