<html>
<head>
<title>Pruebas</title>
</head>
<body>
<?php
class Persona {
    private $nombre;
    public function inicializar($nom) {
        $this->nombre = $nom;
    }
    public function imprimir() {
        echo $this->nombre;
        echo '<br>';
    }
}

$per1 = new Persona();
$per1->inicializar('Juan');
$per1->imprimir();

$per2 = new Persona();
$per2->inicializar('Ana');
$per2->imprimir();
?>
</body>
</html>
