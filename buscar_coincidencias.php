<?php
include('conexion.php');

// Obtener el ID del reporte desde el formulario
$id = $_POST['id_mascota'];

// Obtener los detalles del reporte actual usando el ID
$sql_reporte = "SELECT * FROM mascotas WHERE id_mascota = ?";
$stmt_reporte = $conn->prepare($sql_reporte);
$stmt_reporte->bind_param("i", $id);
$stmt_reporte->execute();
$result_reporte = $stmt_reporte->get_result();

if ($result_reporte->num_rows > 0) {
    $reporte = $result_reporte->fetch_assoc();

    // Definir las variables necesarias a partir del reporte actual
    $lat = $reporte['lat'];
    $lng = $reporte['lng'];
    $tamano = $reporte['tamano'];
    $color = $reporte['color'];
    $tipo = $reporte['tipo'];
    $nombre = $reporte['nombre'];
    $descripcion = $reporte['descripcion'];

    // Determinar el tipo opuesto
    $tipoOpuesto = ($tipo == 'perdido') ? 'encontrado' : 'perdido';

    // Consulta para obtener todos los reportes del tipo opuesto
    $sql = "SELECT * FROM mascotas WHERE id_mascota != ? AND tipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $tipoOpuesto);
    $stmt->execute();
    $result = $stmt->get_result();

    $resultados = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Variables de coincidencia y porcentaje
            $coincidencias = [];
            $similaridad = 0;

            // Comparación de tamaño
            similar_text($tamano, $row['tamano'], $tamano_sim);
            if ($tamano_sim > 0) {
                $coincidencias[] = "Tamaño (" . round($tamano_sim, 2) . "%)";
                $similaridad += ($tamano_sim / 100) * (100 / 4); // Proporcional al porcentaje de nombre
            }

            // Comparación de color
              similar_text($color, $row['color'], $color_sim);
              if ($color_sim > 0) {
                  $coincidencias[] = "Color (" . round($color_sim, 2) . "%)";
                  $similaridad += ($color_sim / 100) * (100 / 4); // Proporcional al porcentaje de nombre
              }

            // Comparar nombres
            similar_text($nombre, $row['nombre'], $nombre_sim);
            if ($nombre_sim > 0) {
                $coincidencias[] = "Nombre (" . round($nombre_sim, 2) . "%)";
                $similaridad += ($nombre_sim / 100) * (100 / 4); // Proporcional al porcentaje de nombre
            }

            // Comparar descripciones
            similar_text($descripcion, $row['descripcion'], $desc_sim);
            if ($desc_sim > 0) {
                $coincidencias[] = "Descripción (" . round($desc_sim, 2) . "%)";
                $similaridad += ($desc_sim / 100) * (100 / 4); // Proporcional al porcentaje de descripción
            }

            // Calcular distancia geográfica
            $distance = haversineGreatCircleDistance($lat, $lng, $row['lat'], $row['lng']);
            if ($distance <= 5) { // Ajustar a 5 km de rango
                $coincidencias[] = "Proximidad geográfica (" . round((5 - $distance) / 5 * 100, 2) . "%)";
                $similaridad += ((5 - $distance) / 5) * 25; // Proporcional al rango de proximidad
            }

            // Solo añadir resultados si hay coincidencias
            if (!empty($coincidencias)) {
                $resultados[] = ['data' => $row, 'similaridad' => $similaridad, 'coincidencias' => $coincidencias];
            }
        }

        // Ordenar resultados por mayor similitud
        usort($resultados, function ($a, $b) {
            return $b['similaridad'] <=> $a['similaridad'];
        });

        // Mostrar resultados ordenados
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Catálogo de Mascotas</title>
            <!-- Incluir Bootstrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .catalogo {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    justify-content: center;
                }
                .mascota {
                    border: 1px solid #ccc;
                    padding: 20px;
                    width: 250px;
                    text-align: center;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .mascota img {
                    width: 100%;
                    height: 150px;
                    object-fit: cover;
                    border-radius: 8px;
                    margin-bottom: 10px;
                }
                .coincidencias {
                    text-align: left;
                }
            </style>
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <h1 class="text-center mb-4">Resultados de la búsqueda</h1>
                <div class="catalogo">
                    <?php
                    foreach ($resultados as $resultado) {
                        $row = $resultado['data'];
                        echo '<div class="mascota bg-white">';
                        echo '<h3>' . $row["nombre"] . '</h3>';
                        echo '<p><strong>Raza:</strong> ' . $row["raza"] . '</p>';
                        echo '<p><strong>Tamaño:</strong> ' . $row["tamano"] . '</p>';
                        echo '<p><strong>Color:</strong> ' . $row["color"] . '</p>';
                        echo '<p><strong>Descripción:</strong> ' . $row["descripcion"] . '</p>';
                        echo '<p><strong>Estado:</strong> ' . $row["tipo"] . '</p>';
                        if ($row["imagen"]) {
                            echo '<img src="data:image/jpeg;base64,' . $row["imagen"] . '" alt="Imagen de ' . $row["nombre"] . '">';
                        } else {
                            echo '<p>Sin imagen disponible</p>';
                        }
                        echo '<div class="coincidencias">';
                        foreach ($resultado['coincidencias'] as $coincidencia) {
                            echo '<p>' . $coincidencia . '</p>';
                        }
                        echo '</div>';
                        echo '<p>Similaridad Total: ' . round($resultado['similaridad'], 2) . '%</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo '<p>No se encontraron coincidencias.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Reporte no encontrado.</p>';
}

$stmt_reporte->close();
$conn->close();

// Función para calcular la distancia geográfica usando la fórmula de Haversine
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
        cos($latFrom) * cos($latTo) *
        sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}
?>