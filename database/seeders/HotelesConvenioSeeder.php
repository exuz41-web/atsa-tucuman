<?php

namespace Database\Seeders;

use App\Models\HotelConvenio;
use Illuminate\Database\Seeder;

class HotelesConvenioSeeder extends Seeder
{
    public function run(): void
    {
        $hoteles = [
            [
                'orden' => 1,
                'nombre' => 'Apart Hotel 21 de Septiembre',
                'tipo' => 'apart_hotel',
                'localidad' => 'Villa Gesell',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Avenida 3 Nº 3585, entre Paseos 135 y 136',
                'descripcion' => 'Apart hotel de la red ATSA en Villa Gesell, a pocos metros del mar y pensado para el descanso familiar.',
                'web_url' => 'https://atsa.org.ar/modal-villa-gesell/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Avenida%203%203585%20Villa%20Gesell',
            ],
            [
                'orden' => 2,
                'nombre' => 'Complejo I’marangatú',
                'tipo' => 'complejo',
                'localidad' => 'Delta del Tigre',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Isla del Delta del Tigre. Consultar embarque y acceso con FATSA.',
                'descripcion' => 'Complejo recreativo con habitaciones, pileta, restaurante, quincho y espacios verdes junto al río.',
                'web_url' => 'https://atsa.org.ar/modal-turismo-tigre/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Complejo%20Imarangatu%20Tigre',
            ],
            [
                'orden' => 3,
                'nombre' => 'Complejo Recreativo Sanidad',
                'tipo' => 'complejo',
                'localidad' => 'Pontevedra',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Pontevedra, Buenos Aires. Dirección a confirmar con FATSA.',
                'descripcion' => 'Espacio recreativo de Sanidad para actividades al aire libre, descanso y encuentro familiar.',
                'web_url' => 'https://atsa.org.ar/turismo/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Complejo%20Recreativo%20Sanidad%20Pontevedra',
            ],
            [
                'orden' => 4,
                'nombre' => 'Hotel Sanidad',
                'tipo' => 'hotel',
                'localidad' => 'Mar del Plata',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Bolívar 2357',
                'descripcion' => 'Hotel de Sanidad en zona céntrica, cerca del centro comercial, la playa y el casino.',
                'web_url' => 'https://atsa.org.ar/hotel-sanidad-en-mar-del-plata/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Bolivar%202357%20Mar%20del%20Plata',
            ],
            [
                'orden' => 5,
                'nombre' => 'Hotel Otto Calace',
                'tipo' => 'hotel',
                'localidad' => 'La Falda',
                'provincia' => 'Córdoba',
                'direccion' => 'Av. General Güemes 198',
                'descripcion' => 'Hotel de la red Sanidad en las sierras cordobesas, con parque, pileta, comedor y espacios recreativos.',
                'web_url' => 'https://www.atsazonanorte.com.ar/es/node/249',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Av.%20General%20Guemes%20198%20La%20Falda%20Cordoba',
            ],
            [
                'orden' => 6,
                'nombre' => 'Hotel FATSA',
                'tipo' => 'hotel',
                'localidad' => 'San Bernardo',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Av. Belgrano 143',
                'descripcion' => 'Hotel familiar en San Bernardo, ubicado a metros del mar y en pleno centro de la ciudad.',
                'web_url' => 'https://www.hotelfatsa-sanbernardo.com/index.html',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Av.%20Belgrano%20143%20San%20Bernardo',
            ],
            [
                'orden' => 7,
                'nombre' => 'Hostería FATSA',
                'tipo' => 'hosteria',
                'localidad' => 'Paso de la Patria',
                'provincia' => 'Corrientes',
                'direccion' => 'San Luis 202',
                'descripcion' => 'Hostería de la red FATSA en destino turístico correntino, con cabañas, habitaciones, playa privada y espacios recreativos.',
                'web_url' => 'https://atsa.org.ar/hosteria-fatsa-en-pasode-la-patria/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=San%20Luis%20202%20Paso%20de%20la%20Patria%20Corrientes',
            ],
            [
                'orden' => 8,
                'nombre' => 'Complejo Recreativo ATSA La Plata',
                'tipo' => 'complejo',
                'localidad' => 'Los Hornos',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Calles 65 y 185',
                'descripcion' => 'Complejo recreativo con piletas, parrillas, buffet, canchas, juegos, baños, vestuarios y servicio médico.',
                'web_url' => 'https://atsa.org.ar/sumamos-dos-nuevos-lugares-para-que-vengas-a-disfrutar/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=65%20y%20185%20Los%20Hornos%20La%20Plata',
            ],
            [
                'orden' => 9,
                'nombre' => 'Camping Necochea',
                'tipo' => 'camping',
                'localidad' => 'Necochea',
                'provincia' => 'Buenos Aires',
                'direccion' => 'Av. 10 y calle 189',
                'descripcion' => 'Camping de Sanidad con arboleda, piletas, quinchos, parrillas, vestuarios, parcelas y salida al mar.',
                'web_url' => 'https://atsalaplata.org.ar/web/turismo/',
                'mapa_url' => 'https://www.google.com/maps/search/?api=1&query=Av.%2010%20y%20189%20Necochea',
            ],
        ];

        foreach ($hoteles as $hotel) {
            HotelConvenio::updateOrCreate(
                ['orden' => $hotel['orden']],
                $hotel + ['activo' => true]
            );
        }
    }
}
