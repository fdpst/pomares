<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvinciasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('provincias')->delete();
        
        \DB::table('provincias')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_pais' => 2,
                'nombre' => 'Almería',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'id_pais' => 2,
                'nombre' => 'Cádiz',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'id_pais' => 2,
                'nombre' => 'Córdoba',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'id_pais' => 2,
                'nombre' => 'Granada',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'id_pais' => 2,
                'nombre' => 'Huelva',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'id_pais' => 2,
                'nombre' => 'Jaén',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'id_pais' => 2,
                'nombre' => 'Málaga',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'id_pais' => 2,
                'nombre' => 'Sevilla',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'id_pais' => 2,
                'nombre' => 'Huesca',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'id_pais' => 2,
                'nombre' => 'Teruel',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'id_pais' => 2,
                'nombre' => 'Zaragoza',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'id_pais' => 2,
                'nombre' => 'Asturias',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'id_pais' => 2,
                'nombre' => 'Balears, Illes',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'id_pais' => 2,
                'nombre' => 'Palmas, Las',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'id_pais' => 2,
                'nombre' => 'Santa Cruz de Tenerife',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'id_pais' => 2,
                'nombre' => 'Cantabria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'id_pais' => 2,
                'nombre' => 'Ávila',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'id_pais' => 2,
                'nombre' => 'Burgos',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'id_pais' => 2,
                'nombre' => 'León',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'id_pais' => 2,
                'nombre' => 'Palencia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'id_pais' => 2,
                'nombre' => 'Salamanca',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'id_pais' => 2,
                'nombre' => 'Segovia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'id_pais' => 2,
                'nombre' => 'Soria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'id_pais' => 2,
                'nombre' => 'Valladolid',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'id_pais' => 2,
                'nombre' => 'Zamora',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'id_pais' => 2,
                'nombre' => 'Albacete',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'id_pais' => 2,
                'nombre' => 'Ciudad Real',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'id_pais' => 2,
                'nombre' => 'Cuenca',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'id_pais' => 2,
                'nombre' => 'Guadalajara',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'id_pais' => 2,
                'nombre' => 'Toledo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'id_pais' => 2,
                'nombre' => 'Barcelona',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'id_pais' => 2,
                'nombre' => 'Girona',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'id_pais' => 2,
                'nombre' => 'Lleida',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'id_pais' => 2,
                'nombre' => 'Tarragona',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'id_pais' => 2,
                'nombre' => 'Alicante',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'id_pais' => 2,
                'nombre' => 'Castellón',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'id_pais' => 2,
                'nombre' => 'Valencia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'id_pais' => 2,
                'nombre' => 'Badajoz',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'id_pais' => 2,
                'nombre' => 'Cáceres',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'id_pais' => 2,
                'nombre' => 'Coruña, A',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'id_pais' => 2,
                'nombre' => 'Lugo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'id_pais' => 2,
                'nombre' => 'Ourense',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'id_pais' => 2,
                'nombre' => 'Pontevedra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'id_pais' => 2,
                'nombre' => 'Madrid',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'id_pais' => 2,
                'nombre' => 'Murcia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'id_pais' => 2,
                'nombre' => 'Navarra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'id_pais' => 2,
                'nombre' => 'Araba/Álava',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'id_pais' => 2,
                'nombre' => 'Bizkaia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'id_pais' => 2,
                'nombre' => 'Gipuzkoa',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'id_pais' => 2,
                'nombre' => 'Rioja, La',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'id_pais' => 2,
                'nombre' => 'Ceuta',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'id_pais' => 2,
                'nombre' => 'Melilla',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'id_pais' => 1,
                'nombre' => 'Región de Baden Wuertt',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'id_pais' => 1,
                'nombre' => 'Baden Wurtemberg',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'id_pais' => 1,
                'nombre' => 'Baviera',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'id_pais' => 1,
                'nombre' => 'Brandenburgo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'id_pais' => 1,
                'nombre' => 'Bremen',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'id_pais' => 1,
                'nombre' => 'Hamburgo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'id_pais' => 1,
                'nombre' => 'Hesse',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'id_pais' => 1,
                'nombre' => 'Berlín',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'id_pais' => 1,
                'nombre' => 'Baja Sajonia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'id_pais' => 1,
                'nombre' => 'Mecklemburgo Pomerania',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'id_pais' => 1,
                'nombre' => 'Renania del Norte West',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'id_pais' => 1,
                'nombre' => 'Renania Palatinado',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'id_pais' => 1,
                'nombre' => 'Saarland',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'id_pais' => 1,
                'nombre' => 'Sajonia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'id_pais' => 1,
                'nombre' => 'Sajonia Anhalt',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'id_pais' => 1,
                'nombre' => 'Sarre',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'id_pais' => 1,
                'nombre' => 'Schleswig-Holstein',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'id_pais' => 1,
                'nombre' => 'Turingia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'id_pais' => 3,
                'nombre' => 'Altos de Francia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'id_pais' => 3,
                'nombre' => 'Auvernia Ródano Alpes',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'id_pais' => 3,
                'nombre' => 'Borgoña Franco Condado',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'id_pais' => 3,
                'nombre' => 'Bretaña',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'id_pais' => 3,
                'nombre' => 'Centre Val de Loire',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'id_pais' => 3,
                'nombre' => 'Corcega',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'id_pais' => 3,
                'nombre' => 'Gran Este',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'id_pais' => 3,
                'nombre' => 'Hauts de France',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'id_pais' => 3,
                'nombre' => 'Isla de Francia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'id_pais' => 3,
                'nombre' => 'Normandía',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'id_pais' => 3,
                'nombre' => 'Nueva Aquitania',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'id_pais' => 3,
                'nombre' => 'Occitania',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'id_pais' => 3,
                'nombre' => 'Países del Loira',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'id_pais' => 3,
                'nombre' => 'Provenza Alpes Costa A',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'id_pais' => 3,
                'nombre' => 'Ultramar',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'id_pais' => 3,
                'nombre' => 'Valle del Loira',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'id_pais' => 4,
                'nombre' => 'Abruzzo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'id_pais' => 4,
                'nombre' => 'Apulia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'id_pais' => 4,
                'nombre' => 'Basilicata',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'id_pais' => 4,
                'nombre' => 'Calabria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'id_pais' => 4,
                'nombre' => 'Campania',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'id_pais' => 4,
                'nombre' => 'Cerdeña',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'id_pais' => 4,
                'nombre' => 'Emilia Romaña',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'id_pais' => 4,
                'nombre' => 'Friuli Venezia Giulia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'id_pais' => 4,
                'nombre' => 'Lazio',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'id_pais' => 4,
                'nombre' => 'Liguria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'id_pais' => 4,
                'nombre' => 'Lombardía',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'id_pais' => 4,
                'nombre' => 'Marche',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'id_pais' => 4,
                'nombre' => 'Molise',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'id_pais' => 4,
                'nombre' => 'Piemonte',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'id_pais' => 4,
                'nombre' => 'Puglia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'id_pais' => 4,
                'nombre' => 'Regione Autonoma Valle',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'id_pais' => 4,
                'nombre' => 'Sardegna',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'id_pais' => 4,
                'nombre' => 'Sicilia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'id_pais' => 4,
                'nombre' => 'Toscana',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'id_pais' => 4,
                'nombre' => 'Trentino Alto Adigio',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'id_pais' => 4,
                'nombre' => 'Umbria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'id_pais' => 4,
                'nombre' => 'Valle de Aosta',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'id_pais' => 4,
                'nombre' => 'Véneto',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'id_pais' => 6,
                'nombre' => 'Inglaterra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'id_pais' => 6,
                'nombre' => 'Irlanda del Norte',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'id_pais' => 6,
                'nombre' => 'Escocia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'id_pais' => 6,
                'nombre' => 'Gales',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'id_pais' => 5,
                'nombre' => 'Azores',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'id_pais' => 5,
                'nombre' => 'Guarda',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'id_pais' => 5,
                'nombre' => 'Aveiro',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'id_pais' => 5,
                'nombre' => 'Beja',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'id_pais' => 5,
                'nombre' => 'Braga',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'id_pais' => 5,
                'nombre' => 'Braganca',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'id_pais' => 5,
                'nombre' => 'Castelo Branco',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'id_pais' => 5,
                'nombre' => 'Coimbra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'id_pais' => 5,
                'nombre' => 'Evora',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'id_pais' => 5,
                'nombre' => 'Faro',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'id_pais' => 5,
                'nombre' => 'Leiria',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'id_pais' => 5,
                'nombre' => 'Lisboa',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'id_pais' => 5,
                'nombre' => 'Portalegre',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'id_pais' => 5,
                'nombre' => 'Santarem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'id_pais' => 5,
                'nombre' => 'Setubal',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'id_pais' => 5,
                'nombre' => 'Viana do Castelo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'id_pais' => 5,
                'nombre' => 'Vila Real',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'id_pais' => 5,
                'nombre' => 'Viseu',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'id_pais' => 5,
                'nombre' => 'Porto',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'id_pais' => 5,
                'nombre' => 'Madeira',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'id_pais' => 7,
                'nombre' => 'Dakar',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'id_pais' => 7,
                'nombre' => 'Diourbel',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'id_pais' => 7,
                'nombre' => 'Fatick',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'id_pais' => 7,
                'nombre' => 'Kaffrine',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'id_pais' => 7,
                'nombre' => 'Kolda',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'id_pais' => 7,
                'nombre' => 'Kédougou',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'id_pais' => 7,
                'nombre' => 'Louga',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'id_pais' => 7,
                'nombre' => 'Matam',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'id_pais' => 7,
                'nombre' => 'Saint-Louis',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'id_pais' => 7,
                'nombre' => 'Tambacounda',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'id_pais' => 7,
                'nombre' => 'Kaolack',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'id_pais' => 7,
                'nombre' => 'Sédhiou',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'id_pais' => 7,
                'nombre' => 'Thiès',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'id_pais' => 7,
                'nombre' => 'Ziguinchor',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 148,
                'id_pais' => 8,
                'nombre' => 'Casablanca',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 149,
                'id_pais' => 8,
                'nombre' => 'Kenitra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 150,
                'id_pais' => 8,
                'nombre' => 'El Yadida',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 151,
                'id_pais' => 8,
                'nombre' => 'Marrakech',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'id_pais' => 8,
                'nombre' => 'Fez',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 153,
                'id_pais' => 8,
                'nombre' => 'Settat',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'id_pais' => 8,
                'nombre' => 'Beni Melal',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'id_pais' => 8,
                'nombre' => 'Safí',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 156,
                'id_pais' => 8,
                'nombre' => 'Salé',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'id_pais' => 8,
                'nombre' => 'Tarudant',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'id_pais' => 8,
                'nombre' => 'Tánger-Arcila',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 159,
                'id_pais' => 8,
                'nombre' => 'El Kelaa des Sraghna',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'id_pais' => 8,
                'nombre' => 'Taza',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'id_pais' => 8,
                'nombre' => 'Nador',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'id_pais' => 8,
                'nombre' => 'Mequinez',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'id_pais' => 8,
                'nombre' => 'Sidi Kacem',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 164,
                'id_pais' => 8,
                'nombre' => 'Taunat',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 165,
                'id_pais' => 8,
                'nombre' => 'Rabat',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 166,
                'id_pais' => 8,
                'nombre' => 'Tetuánnota 1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 167,
                'id_pais' => 8,
                'nombre' => 'Errachidia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 168,
                'id_pais' => 8,
                'nombre' => 'Xauen',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 169,
                'id_pais' => 8,
                'nombre' => 'Jemisset',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 170,
                'id_pais' => 8,
                'nombre' => 'Jenifra',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 171,
                'id_pais' => 8,
                'nombre' => 'Azilal',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 172,
                'id_pais' => 8,
                'nombre' => 'Uarzazat',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 173,
                'id_pais' => 8,
                'nombre' => 'Juribga',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 174,
                'id_pais' => 8,
                'nombre' => 'Agadir Ida-Outanane',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 175,
                'id_pais' => 8,
                'nombre' => 'Al Hauz',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 176,
                'id_pais' => 8,
                'nombre' => 'Oujda-Angad',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 177,
                'id_pais' => 8,
                'nombre' => 'Larache',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 178,
                'id_pais' => 8,
                'nombre' => 'Esauira',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 179,
                'id_pais' => 8,
                'nombre' => 'Inezgane-Aït Melloul',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 180,
                'id_pais' => 8,
                'nombre' => 'Alhucemas',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 181,
                'id_pais' => 8,
                'nombre' => 'Sjirat-Témara',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 182,
                'id_pais' => 8,
                'nombre' => 'Tiznit',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 183,
                'id_pais' => 8,
                'nombre' => 'Chichaoua',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 184,
                'id_pais' => 8,
                'nombre' => 'Mohammedía',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 185,
                'id_pais' => 8,
                'nombre' => 'Chtouka-Aït Baha',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 186,
                'id_pais' => 8,
                'nombre' => 'Zagora',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 187,
                'id_pais' => 8,
                'nombre' => 'Berkan',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 188,
                'id_pais' => 8,
                'nombre' => 'Sefrú',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 189,
                'id_pais' => 8,
                'nombre' => 'Nouaceur',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 190,
                'id_pais' => 8,
                'nombre' => 'El Hayeb',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 191,
                'id_pais' => 8,
                'nombre' => 'El Aaiún',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 192,
                'id_pais' => 8,
                'nombre' => 'Taurirt',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 193,
                'id_pais' => 8,
                'nombre' => 'Benslimane',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 194,
                'id_pais' => 8,
                'nombre' => 'Bulman',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 195,
                'id_pais' => 8,
                'nombre' => 'Guelmin',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 196,
                'id_pais' => 8,
                'nombre' => 'Mulay Yacub',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 197,
                'id_pais' => 8,
                'nombre' => 'Ifrane',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 198,
                'id_pais' => 8,
                'nombre' => 'Figuig',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 199,
                'id_pais' => 8,
                'nombre' => 'Mediuna',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 200,
                'id_pais' => 8,
                'nombre' => 'Tata',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 201,
                'id_pais' => 8,
                'nombre' => 'Yerada',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 202,
                'id_pais' => 8,
                'nombre' => 'Fahs-Anyera',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 203,
                'id_pais' => 8,
                'nombre' => 'Río de Oro-Dajla',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 204,
                'id_pais' => 8,
                'nombre' => 'Tan-Tan',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 205,
                'id_pais' => 8,
                'nombre' => 'Esmara',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 206,
                'id_pais' => 8,
                'nombre' => 'Bojador',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 207,
                'id_pais' => 8,
                'nombre' => 'Assa-Zag',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 208,
                'id_pais' => 8,
                'nombre' => 'Auserd',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 209,
                'id_pais' => 8,
                'nombre' => 'Marruecos',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}