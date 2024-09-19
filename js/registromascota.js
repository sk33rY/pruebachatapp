let currentStep = 0;
const steps = document.querySelectorAll('.step');
const nextBtns = document.querySelectorAll('#nextBtn');
const prevBtns = document.querySelectorAll('#prevBtn');
const razaSelect = document.getElementById('raza');
const tipoAnimalSelect = document.getElementById('tipo_animal');
let map; // Almacena el objeto del mapa
let mapInitialized = false;  // Bandera para saber si el mapa ya se ha inicializado

// Mostrar el paso inicial
function showStep(n) {
    steps.forEach((step, index) => {
        step.classList.remove('active');
        if (index === n) {
            step.classList.add('active');
        }
    });

    // Inicializar o redibujar el mapa cuando se llega al paso 9
    if (n === 8) {
        if (!mapInitialized) {
            initMap(); // Solo inicializa el mapa una vez
            mapInitialized = true;
        } else {
            google.maps.event.trigger(map, 'resize'); // Forzar redibujo si ya está inicializado
        }
    }
}

// Avanzar al siguiente paso
nextBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });
});

// Retroceder al paso anterior
prevBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
});

// Función para inicializar el mapa
function initMap() {
    map = new google.maps.Map(document.getElementById('mapa'), {
        center: {lat: 4.7352573, lng: -74.0182495},
        zoom: 13
    });

    let geocoder = new google.maps.Geocoder();
    let marker;

    document.getElementById('geocode-btn').addEventListener('click', function() {
        geocodeAddress(geocoder, map);
    });

    document.getElementById('ubicacion-btn').addEventListener('click', function() {
        obtenerUbicacion(map);
    });

    map.addListener('click', function(event) {
        let lat = event.latLng.lat();
        let lng = event.latLng.lng();

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map
            });
        }
    });
}

function geocodeAddress(geocoder, map) {
    let address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            map.setCenter(results[0].geometry.location);
            let marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            document.getElementById('lat').value = results[0].geometry.location.lat();
            document.getElementById('lng').value = results[0].geometry.location.lng();
        } else {
            alert('Geocode no tuvo éxito por la siguiente razón: ' + status);
        }
    });
}

function obtenerUbicacion(map) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            let marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map
            });

            map.setCenter({lat: lat, lng: lng});
            map.setZoom(15);

        }, function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Usuario negó el permiso para la geolocalización.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("La información de la ubicación no está disponible.");
                    break;
                case error.TIMEOUT:
                    alert("La solicitud para obtener la ubicación ha expirado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Se ha producido un error desconocido.");
                    break;
            }
        });
    } else {
        alert("La geolocalización no es compatible con este navegador.");
    }
}

// Mostrar el primer paso al cargar
document.addEventListener("DOMContentLoaded", function() {
    showStep(currentStep);
});
