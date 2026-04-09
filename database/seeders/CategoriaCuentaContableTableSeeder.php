<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriaCuentaContableTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categoria_cuenta_contable')->delete();
        
        \DB::table('categoria_cuenta_contable')->insert(array (
            0 => 
            array (
                'id' => 2,
                'cuenta' => '1',
                'denominacion' => 'FINANCIACIÓN BÁSICA',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            1 => 
            array (
                'id' => 3,
                'cuenta' => '10',
                'denominacion' => ' CAPITAL',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            2 => 
            array (
                'id' => 4,
                'cuenta' => '100',
                'denominacion' => ' Capital social',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            3 => 
            array (
                'id' => 5,
                'cuenta' => '101',
                'denominacion' => ' Fondo social',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            4 => 
            array (
                'id' => 6,
                'cuenta' => '102',
                'denominacion' => ' Capital',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            5 => 
            array (
                'id' => 7,
                'cuenta' => '103',
                'denominacion' => ' Socios por desembolsos no exigidos',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            6 => 
            array (
                'id' => 8,
                'cuenta' => '1030',
                'denominacion' => ' Socios por desembolsos no exigidos, capital social',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            7 => 
            array (
                'id' => 9,
                'cuenta' => '1034',
                'denominacion' => ' Socios por desembolsos no exigidos, capital pendiente de inscripción',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            8 => 
            array (
                'id' => 10,
                'cuenta' => '104',
                'denominacion' => ' Socios por aportaciones no dinerarias pendientes',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            9 => 
            array (
                'id' => 11,
                'cuenta' => '1040',
                'denominacion' => ' Socios por aportaciones no dinerarias pendientes, capital social',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            10 => 
            array (
                'id' => 12,
                'cuenta' => '1044',
                'denominacion' => ' Socios por aportaciones no dinerarias pendientes, capital pendiente de inscripción',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            11 => 
            array (
                'id' => 13,
                'cuenta' => '108',
                'denominacion' => ' Acciones o participaciones propias en situaciones especiales',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            12 => 
            array (
                'id' => 14,
                'cuenta' => '109',
                'denominacion' => ' Acciones o participaciones propias para reducción de capital',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            13 => 
            array (
                'id' => 15,
                'cuenta' => '11',
                'denominacion' => ' RESERVAS Y OTROS INSTRUMENTOS DE PATRIMONIO',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            14 => 
            array (
                'id' => 16,
                'cuenta' => '110',
                'denominacion' => ' Prima de emisión o asunción',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            15 => 
            array (
                'id' => 17,
                'cuenta' => '111',
                'denominacion' => ' Otros instrumentos de patrimonio neto',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            16 => 
            array (
                'id' => 18,
                'cuenta' => '1110',
                'denominacion' => ' Patrimonio neto por emisión de instrumentos financieros compuestos',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            17 => 
            array (
                'id' => 19,
                'cuenta' => '1111',
                'denominacion' => ' Resto de instrumentos de patrimonio neto',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            18 => 
            array (
                'id' => 20,
                'cuenta' => '112',
                'denominacion' => ' Reserva legal',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            19 => 
            array (
                'id' => 21,
                'cuenta' => '113',
                'denominacion' => ' Reservas voluntarias',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            20 => 
            array (
                'id' => 22,
                'cuenta' => '114',
                'denominacion' => ' Reservas especiales',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            21 => 
            array (
                'id' => 23,
                'cuenta' => '1140',
                'denominacion' => ' Reservas para acciones o participaciones de la sociedad dominante',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            22 => 
            array (
                'id' => 24,
                'cuenta' => '1141',
                'denominacion' => ' Reservas estatutarias',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            23 => 
            array (
                'id' => 25,
                'cuenta' => '1142',
                'denominacion' => ' Reserva por capital amortizado',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            24 => 
            array (
                'id' => 26,
                'cuenta' => '1143',
                'denominacion' => ' Reserva por fondo de comercio',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            25 => 
            array (
                'id' => 27,
                'cuenta' => '1144',
                'denominacion' => ' Reservas por acciones propias aceptadas en garantía',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            26 => 
            array (
                'id' => 28,
                'cuenta' => '115',
                'denominacion' => ' Reservas por pérdidas y ganancias actuariales y otros ajustes',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            27 => 
            array (
                'id' => 29,
                'cuenta' => '118',
                'denominacion' => ' Aportaciones de socios o propietarios',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            28 => 
            array (
                'id' => 30,
                'cuenta' => '119',
                'denominacion' => ' Diferencias por ajuste del capital a euros',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            29 => 
            array (
                'id' => 31,
                'cuenta' => '12',
                'denominacion' => ' RESULTADOS PENDIENTES DE APLICACIÓN',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            30 => 
            array (
                'id' => 32,
                'cuenta' => '120',
                'denominacion' => ' Remanente',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            31 => 
            array (
                'id' => 33,
                'cuenta' => '121',
                'denominacion' => ' Resultados negativos de ejercicios anteriores',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            32 => 
            array (
                'id' => 34,
                'cuenta' => '129',
                'denominacion' => ' Resultado del ejercicio',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            33 => 
            array (
                'id' => 35,
                'cuenta' => '13',
                'denominacion' => ' SUBVENCIONES, DONACIONES Y AJUSTES POR CAMBIOS DE VALOR',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            34 => 
            array (
                'id' => 36,
                'cuenta' => '130',
                'denominacion' => ' Subvenciones oficiales de capital',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            35 => 
            array (
                'id' => 37,
                'cuenta' => '131',
                'denominacion' => ' Donaciones y legados de capital',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            36 => 
            array (
                'id' => 38,
                'cuenta' => '132',
                'denominacion' => ' Otras subvenciones, donaciones y legados',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            37 => 
            array (
                'id' => 39,
                'cuenta' => '133',
                'denominacion' => ' Ajustes por valoración en activos financieros disponibles para la venta',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            38 => 
            array (
                'id' => 40,
                'cuenta' => '134',
                'denominacion' => ' Operaciones de cobertura',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            39 => 
            array (
                'id' => 41,
                'cuenta' => '1340',
                'denominacion' => ' Cobertura de flujos de efectivo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            40 => 
            array (
                'id' => 42,
                'cuenta' => '1341',
                'denominacion' => ' Cobertura de una inversión neta en un negocio en el extranjero',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            41 => 
            array (
                'id' => 43,
                'cuenta' => '135',
                'denominacion' => ' Diferencias de conversión',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            42 => 
            array (
                'id' => 44,
                'cuenta' => '136',
                'denominacion' => ' Ajustes por valoración en activos no corrientes y grupos enajenables de elementos, mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            43 => 
            array (
                'id' => 45,
                'cuenta' => '137',
                'denominacion' => ' Ingresos fiscales a distribuir en varios ejercicios',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            44 => 
            array (
                'id' => 46,
                'cuenta' => '1370',
                'denominacion' => ' Ingresos fiscales por diferencias permanentes a distribuir en varios ejercicios',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            45 => 
            array (
                'id' => 47,
                'cuenta' => '1371',
                'denominacion' => ' Ingresos fiscales por deducciones y bonificaciones a distribuir en varios ejercicios',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            46 => 
            array (
                'id' => 48,
                'cuenta' => '14',
                'denominacion' => ' PROVISIONES',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            47 => 
            array (
                'id' => 49,
                'cuenta' => '140',
                'denominacion' => ' Provisión por retribuciones a largo plazo al personal',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            48 => 
            array (
                'id' => 50,
                'cuenta' => '141',
                'denominacion' => ' Provisión para impuestos',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            49 => 
            array (
                'id' => 51,
                'cuenta' => '142',
                'denominacion' => ' Provisión para otras responsabilidades',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            50 => 
            array (
                'id' => 52,
                'cuenta' => '143',
                'denominacion' => ' Provisión por desmantelamiento, retiro o rehabilitación del inmovilizado',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            51 => 
            array (
                'id' => 53,
                'cuenta' => '145',
                'denominacion' => ' Provisión para actuaciones medioambientales',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            52 => 
            array (
                'id' => 54,
                'cuenta' => '146',
                'denominacion' => ' Provisión para reestructuraciones',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            53 => 
            array (
                'id' => 55,
                'cuenta' => '147',
                'denominacion' => ' Provisión por transacciones con pagos basados en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            54 => 
            array (
                'id' => 56,
                'cuenta' => '15',
                'denominacion' => ' DEUDAS A LARGO PLAZO CON CARACTERÍSTICAS ESPECIALES',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            55 => 
            array (
                'id' => 57,
                'cuenta' => '150',
                'denominacion' => ' Acciones o participaciones a largo plazo consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            56 => 
            array (
                'id' => 58,
                'cuenta' => '153',
                'denominacion' => ' Desembolsos no exigidos por acciones o participaciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            57 => 
            array (
                'id' => 59,
                'cuenta' => '1533',
                'denominacion' => ' Desembolsos no exigidos, empresas del grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            58 => 
            array (
                'id' => 60,
                'cuenta' => '1534',
                'denominacion' => ' Desembolsos no exigidos, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            59 => 
            array (
                'id' => 61,
                'cuenta' => '1535',
                'denominacion' => ' Desembolsos no exigidos, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            60 => 
            array (
                'id' => 62,
                'cuenta' => '1536',
                'denominacion' => ' Otros desembolsos no exigidos',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            61 => 
            array (
                'id' => 63,
                'cuenta' => '154',
                'denominacion' => ' Aportaciones no dinerarias pendientes por acciones o participaciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            62 => 
            array (
                'id' => 64,
                'cuenta' => '1543',
                'denominacion' => ' Aportaciones no dinerarias pendientes, empresas del grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            63 => 
            array (
                'id' => 65,
                'cuenta' => '1544',
                'denominacion' => ' Aportaciones no dinerarias pendientes, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            64 => 
            array (
                'id' => 66,
                'cuenta' => '1545',
                'denominacion' => ' Aportaciones no dinerarias pendientes, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            65 => 
            array (
                'id' => 67,
                'cuenta' => '1546',
                'denominacion' => ' Otras aportaciones no dinerarias pendientes',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            66 => 
            array (
                'id' => 68,
                'cuenta' => '16',
                'denominacion' => ' DEUDAS A LARGO PLAZO CON PARTES VINCULADAS',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            67 => 
            array (
                'id' => 69,
                'cuenta' => '160',
                'denominacion' => ' Deudas a largo plazo con entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            68 => 
            array (
                'id' => 70,
                'cuenta' => '1603',
                'denominacion' => ' Deudas a largo plazo con entidades de crédito, empresas del grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            69 => 
            array (
                'id' => 71,
                'cuenta' => '1604',
                'denominacion' => ' Deudas a largo plazo con entidades de crédito, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            70 => 
            array (
                'id' => 72,
                'cuenta' => '1605',
                'denominacion' => ' Deudas a largo plazo con otras entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            71 => 
            array (
                'id' => 73,
                'cuenta' => '161',
                'denominacion' => ' Proveedores de inmovilizado a largo plazo, partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            72 => 
            array (
                'id' => 74,
                'cuenta' => '1613',
                'denominacion' => ' Proveedores de inmovilizado a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            73 => 
            array (
                'id' => 75,
                'cuenta' => '1614',
                'denominacion' => ' Proveedores de inmovilizado a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            74 => 
            array (
                'id' => 76,
                'cuenta' => '1615',
                'denominacion' => ' Proveedores de inmovilizado a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            75 => 
            array (
                'id' => 77,
                'cuenta' => '162',
                'denominacion' => ' Acreedores por arrendamiento financiero a largo plazo, partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            76 => 
            array (
                'id' => 78,
                'cuenta' => '1623',
                'denominacion' => ' Acreedores por arrendamiento financiero a largo plazo, empresas de grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            77 => 
            array (
                'id' => 79,
                'cuenta' => '1624',
                'denominacion' => ' Acreedores por arrendamiento financiero a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            78 => 
            array (
                'id' => 80,
                'cuenta' => '1625',
                'denominacion' => ' Acreedores por arrendamiento financiero a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            79 => 
            array (
                'id' => 81,
                'cuenta' => '163',
                'denominacion' => ' Otras deudas a largo plazo con partes vinculadas ',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            80 => 
            array (
                'id' => 82,
                'cuenta' => '1633',
                'denominacion' => ' Otras deudas a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            81 => 
            array (
                'id' => 83,
                'cuenta' => '1634',
                'denominacion' => ' Otras deudas a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            82 => 
            array (
                'id' => 84,
                'cuenta' => '1635',
                'denominacion' => ' Otras deudas a largo plazo, con otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            83 => 
            array (
                'id' => 85,
                'cuenta' => '17',
                'denominacion' => ' DEUDAS A LARGO PLAZO POR PRÉSTAMOS RECIBIDOS, EMPRÉSTITOS Y OTROS CONCEPTOS',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            84 => 
            array (
                'id' => 86,
                'cuenta' => '170',
                'denominacion' => ' Deudas a largo plazo con entidades de crédito',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            85 => 
            array (
                'id' => 87,
                'cuenta' => '171',
                'denominacion' => ' Deudas a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            86 => 
            array (
                'id' => 88,
                'cuenta' => '172',
                'denominacion' => ' Deudas a largo plazo transformables en subvenciones, donaciones y legados',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            87 => 
            array (
                'id' => 89,
                'cuenta' => '173',
                'denominacion' => ' Proveedores de inmovilizado a largo plazo ',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            88 => 
            array (
                'id' => 90,
                'cuenta' => '174',
                'denominacion' => ' Acreedores por arrendamiento financiero a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            89 => 
            array (
                'id' => 91,
                'cuenta' => '175',
                'denominacion' => ' Efectos a pagar a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            90 => 
            array (
                'id' => 92,
                'cuenta' => '176',
                'denominacion' => ' Pasivos por derivados financieros a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            91 => 
            array (
                'id' => 93,
                'cuenta' => '1765',
                'denominacion' => ' Pasivos por derivados financieros a largo plazo, cartera de negociación',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            92 => 
            array (
                'id' => 94,
                'cuenta' => '1768',
                'denominacion' => ' Pasivos por derivados financieros a largo plazo, instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            93 => 
            array (
                'id' => 95,
                'cuenta' => '177',
                'denominacion' => ' Obligaciones y bonos',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            94 => 
            array (
                'id' => 96,
                'cuenta' => '178',
                'denominacion' => ' Obligaciones y bonos convertibles',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            95 => 
            array (
                'id' => 97,
                'cuenta' => '179',
                'denominacion' => ' Deudas representadas en otros valores negociables',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            96 => 
            array (
                'id' => 98,
                'cuenta' => '18',
                'denominacion' => ' PASIVOS POR FIANZAS, GARANTÍAS Y OTROS CONCEPTOS A LARGO PLAZO',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            97 => 
            array (
                'id' => 99,
                'cuenta' => '180',
                'denominacion' => ' Fianzas recibidas a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            98 => 
            array (
                'id' => 100,
                'cuenta' => '181',
                'denominacion' => ' Anticipos recibidos por ventas o prestaciones de servicios a largo plazo',
                'created_at' => '2024-04-15 15:02:58',
                'updated_at' => '2024-04-15 15:02:58',
            ),
            99 => 
            array (
                'id' => 101,
                'cuenta' => '185',
                'denominacion' => ' Depósitos recibidos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            100 => 
            array (
                'id' => 102,
                'cuenta' => '189',
                'denominacion' => ' Garantías financieras a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            101 => 
            array (
                'id' => 103,
                'cuenta' => '19',
                'denominacion' => ' SITUACIONES TRANSITORIAS DE FINANCIACIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            102 => 
            array (
                'id' => 104,
                'cuenta' => '190',
                'denominacion' => ' Acciones o participaciones emitidas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            103 => 
            array (
                'id' => 105,
                'cuenta' => '192',
                'denominacion' => ' Suscriptores de acciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            104 => 
            array (
                'id' => 106,
                'cuenta' => '194',
                'denominacion' => ' Capital emitido pendiente de inscripción',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            105 => 
            array (
                'id' => 107,
                'cuenta' => '195',
                'denominacion' => ' Acciones o participaciones emitidas consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            106 => 
            array (
                'id' => 108,
                'cuenta' => '197',
                'denominacion' => ' Suscriptores de acciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            107 => 
            array (
                'id' => 109,
                'cuenta' => '199',
                'denominacion' => ' Acciones o participaciones emitidas consideradas como pasivos financieros pendientes de inscripción',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            108 => 
            array (
                'id' => 110,
                'cuenta' => '2',
                'denominacion' => 'ACTIVO NO CORRIENTE',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            109 => 
            array (
                'id' => 111,
                'cuenta' => '20',
                'denominacion' => ' INMOVILIZACIONES INTANGIBLES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            110 => 
            array (
                'id' => 112,
                'cuenta' => '200',
                'denominacion' => ' Investigación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            111 => 
            array (
                'id' => 113,
                'cuenta' => '201',
                'denominacion' => ' Desarrollo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            112 => 
            array (
                'id' => 114,
                'cuenta' => '202',
                'denominacion' => ' Concesiones administrativas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            113 => 
            array (
                'id' => 115,
                'cuenta' => '203',
                'denominacion' => ' Propiedad industrial',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            114 => 
            array (
                'id' => 116,
                'cuenta' => '204',
                'denominacion' => ' Fondo de comercio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            115 => 
            array (
                'id' => 117,
                'cuenta' => '205',
                'denominacion' => ' Derechos de traspaso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            116 => 
            array (
                'id' => 118,
                'cuenta' => '206',
                'denominacion' => ' Aplicaciones informáticas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            117 => 
            array (
                'id' => 119,
                'cuenta' => '209',
                'denominacion' => ' Anticipos para inmovilizaciones intangibles',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            118 => 
            array (
                'id' => 120,
                'cuenta' => '21',
                'denominacion' => ' INMOVILIZACIONES MATERIALES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            119 => 
            array (
                'id' => 121,
                'cuenta' => '210',
                'denominacion' => ' Terrenos y bienes naturales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            120 => 
            array (
                'id' => 122,
                'cuenta' => '211',
                'denominacion' => ' Construcciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            121 => 
            array (
                'id' => 123,
                'cuenta' => '212',
                'denominacion' => ' Instalaciones técnicas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            122 => 
            array (
                'id' => 124,
                'cuenta' => '213',
                'denominacion' => ' Maquinaria',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            123 => 
            array (
                'id' => 125,
                'cuenta' => '214',
                'denominacion' => ' Utillaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            124 => 
            array (
                'id' => 126,
                'cuenta' => '215',
                'denominacion' => ' Otras instalaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            125 => 
            array (
                'id' => 127,
                'cuenta' => '216',
                'denominacion' => ' Mobiliario',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            126 => 
            array (
                'id' => 128,
                'cuenta' => '217',
                'denominacion' => ' Equipos para procesos de información',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            127 => 
            array (
                'id' => 129,
                'cuenta' => '218',
                'denominacion' => ' Elementos de transporte',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            128 => 
            array (
                'id' => 130,
                'cuenta' => '219',
                'denominacion' => ' Otro inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            129 => 
            array (
                'id' => 131,
                'cuenta' => '22',
                'denominacion' => ' INVERSIONES INMOBILIARIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            130 => 
            array (
                'id' => 132,
                'cuenta' => '220',
                'denominacion' => ' Inversiones en terrenos y bienes naturales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            131 => 
            array (
                'id' => 133,
                'cuenta' => '221',
                'denominacion' => ' Inversiones en construcciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            132 => 
            array (
                'id' => 134,
                'cuenta' => '23',
                'denominacion' => ' INMOVILIZACIONES MATERIALES EN CURSO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            133 => 
            array (
                'id' => 135,
                'cuenta' => '230',
                'denominacion' => ' Adaptación de terrenos y bienes naturales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            134 => 
            array (
                'id' => 136,
                'cuenta' => '231',
                'denominacion' => ' Construcciones en curso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            135 => 
            array (
                'id' => 137,
                'cuenta' => '232',
                'denominacion' => ' Instalaciones técnicas en montaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            136 => 
            array (
                'id' => 138,
                'cuenta' => '233',
                'denominacion' => ' Maquinaria en montaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            137 => 
            array (
                'id' => 139,
                'cuenta' => '237',
                'denominacion' => ' Equipos para procesos de información en montaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            138 => 
            array (
                'id' => 140,
                'cuenta' => '239',
                'denominacion' => ' Anticipos para inmovilizaciones materiales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            139 => 
            array (
                'id' => 141,
                'cuenta' => '24',
                'denominacion' => ' INVERSIONES FINANCIERAS A LARGO PLAZO EN PARTES VINCULADAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            140 => 
            array (
                'id' => 142,
                'cuenta' => '240',
                'denominacion' => ' Participaciones a largo plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            141 => 
            array (
                'id' => 143,
                'cuenta' => '2403',
                'denominacion' => ' Participaciones a largo plazo en empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            142 => 
            array (
                'id' => 144,
                'cuenta' => '2404',
                'denominacion' => ' Participaciones a largo plazo en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            143 => 
            array (
                'id' => 145,
                'cuenta' => '2405',
                'denominacion' => ' Participaciones a largo plazo en otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            144 => 
            array (
                'id' => 146,
                'cuenta' => '241',
                'denominacion' => ' Valores representativos de deuda a largo plazo de partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            145 => 
            array (
                'id' => 147,
                'cuenta' => '2413',
                'denominacion' => ' Valores representativos de deuda a largo plazo de empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            146 => 
            array (
                'id' => 148,
                'cuenta' => '2414',
                'denominacion' => ' Valores representativos de deuda a largo plazo de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            147 => 
            array (
                'id' => 149,
                'cuenta' => '2415',
                'denominacion' => ' Valores representativos de deuda a largo plazo de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            148 => 
            array (
                'id' => 150,
                'cuenta' => '242',
                'denominacion' => ' Créditos a largo plazo a partes vinculadas ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            149 => 
            array (
                'id' => 151,
                'cuenta' => '2423',
                'denominacion' => ' Créditos a largo plazo a empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            150 => 
            array (
                'id' => 152,
                'cuenta' => '2424',
                'denominacion' => ' Créditos a largo plazo a empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            151 => 
            array (
                'id' => 153,
                'cuenta' => '2425',
                'denominacion' => ' Créditos a largo plazo a otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            152 => 
            array (
                'id' => 154,
                'cuenta' => '249',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a largo plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            153 => 
            array (
                'id' => 155,
                'cuenta' => '2493',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a largo plazo en em presas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            154 => 
            array (
                'id' => 156,
                'cuenta' => '2494',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a largo plazo en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            155 => 
            array (
                'id' => 157,
                'cuenta' => '2495',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a largo plazo en otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            156 => 
            array (
                'id' => 158,
                'cuenta' => '25',
                'denominacion' => ' OTRAS INVERSIONES FINANCIERAS A LARGO PLAZO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            157 => 
            array (
                'id' => 159,
                'cuenta' => '250',
                'denominacion' => ' Inversiones financieras a largo plazo en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            158 => 
            array (
                'id' => 160,
                'cuenta' => '251',
                'denominacion' => ' Valores representativos de deuda a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            159 => 
            array (
                'id' => 161,
                'cuenta' => '252',
                'denominacion' => ' Créditos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            160 => 
            array (
                'id' => 162,
                'cuenta' => '253',
                'denominacion' => ' Créditos a largo plazo por enajenación de inmovilizado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            161 => 
            array (
                'id' => 163,
                'cuenta' => '254',
                'denominacion' => ' Créditos a largo plazo al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            162 => 
            array (
                'id' => 164,
                'cuenta' => '255',
                'denominacion' => ' Activos por derivados financieros a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            163 => 
            array (
                'id' => 165,
                'cuenta' => '2550',
                'denominacion' => ' Activos por derivados financieros a largo plazo, cartera de negociación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            164 => 
            array (
                'id' => 166,
                'cuenta' => '2553',
                'denominacion' => ' Activos por derivados financieros a largo plazo, instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            165 => 
            array (
                'id' => 167,
                'cuenta' => '257',
                'denominacion' => ' Derechos de reembolso derivados de contratos de seguro relativos a retribuciones a largo plazo al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            166 => 
            array (
                'id' => 168,
                'cuenta' => '258',
                'denominacion' => ' Imposiciones a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            167 => 
            array (
                'id' => 169,
                'cuenta' => '259',
                'denominacion' => ' Desembolsos pendientes sobre participaciones en el patrimonio neto a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            168 => 
            array (
                'id' => 170,
                'cuenta' => '26',
                'denominacion' => ' FIANZAS Y DEPÓSITOS CONSTITUIDOS A LARGO PLAZO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            169 => 
            array (
                'id' => 171,
                'cuenta' => '260',
                'denominacion' => ' Fianzas constituidas a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            170 => 
            array (
                'id' => 172,
                'cuenta' => '265',
                'denominacion' => ' Depósitos constituidos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            171 => 
            array (
                'id' => 173,
                'cuenta' => '28',
                'denominacion' => ' AMORTIZACIÓN ACUMULADA DEL INMOVILIZADO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            172 => 
            array (
                'id' => 174,
                'cuenta' => '280',
                'denominacion' => ' Amortización acumulada del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            173 => 
            array (
                'id' => 175,
                'cuenta' => '2800',
                'denominacion' => ' Amortización acumulada de investigación ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            174 => 
            array (
                'id' => 176,
                'cuenta' => '2801',
                'denominacion' => ' Amortización acumulada de desarrollo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            175 => 
            array (
                'id' => 177,
                'cuenta' => '2802',
                'denominacion' => ' Amortización acumulada de concesiones administrativas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            176 => 
            array (
                'id' => 178,
                'cuenta' => '2803',
                'denominacion' => ' Amortización acumulada de propiedad industrial',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            177 => 
            array (
                'id' => 179,
                'cuenta' => '2805',
                'denominacion' => ' Amortización acumulada de derechos de traspaso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            178 => 
            array (
                'id' => 180,
                'cuenta' => '2806',
                'denominacion' => ' Amortización acumulada de aplicaciones informáticas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            179 => 
            array (
                'id' => 181,
                'cuenta' => '281',
                'denominacion' => ' Amortización acumulada del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            180 => 
            array (
                'id' => 182,
                'cuenta' => '2811',
                'denominacion' => ' Amortización acumulada de construcciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            181 => 
            array (
                'id' => 183,
                'cuenta' => '2812',
                'denominacion' => ' Amortización acumulada de instalaciones técnicas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            182 => 
            array (
                'id' => 184,
                'cuenta' => '2813',
                'denominacion' => ' Amortización acumulada de maquinaria',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            183 => 
            array (
                'id' => 185,
                'cuenta' => '2814',
                'denominacion' => ' Amortización acumulada de utillaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            184 => 
            array (
                'id' => 186,
                'cuenta' => '2815',
                'denominacion' => ' Amortización acumulada de otras instalaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            185 => 
            array (
                'id' => 187,
                'cuenta' => '2816',
                'denominacion' => ' Amortización acumulada de mobiliario',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            186 => 
            array (
                'id' => 188,
                'cuenta' => '2817',
                'denominacion' => ' Amortización acumulada de equipos para procesos de información',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            187 => 
            array (
                'id' => 189,
                'cuenta' => '2818',
                'denominacion' => ' Amortización acumulada de elementos de transporte',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            188 => 
            array (
                'id' => 190,
                'cuenta' => '2819',
                'denominacion' => ' Amortización acumulada de otro inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            189 => 
            array (
                'id' => 191,
                'cuenta' => '282',
                'denominacion' => ' Amortización acumulada de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            190 => 
            array (
                'id' => 192,
                'cuenta' => '29',
                'denominacion' => ' DETERIORO DE VALOR DE ACTIVOS NO CORRIENTES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            191 => 
            array (
                'id' => 193,
                'cuenta' => '290',
                'denominacion' => ' Deterioro de valor del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            192 => 
            array (
                'id' => 194,
                'cuenta' => '2900',
                'denominacion' => ' Deterioro de valor de investigación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            193 => 
            array (
                'id' => 195,
                'cuenta' => '2901',
                'denominacion' => ' Deterioro del valor de desarrollo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            194 => 
            array (
                'id' => 196,
                'cuenta' => '2902',
                'denominacion' => ' Deterioro de valor de concesiones administrativas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            195 => 
            array (
                'id' => 197,
                'cuenta' => '2903',
                'denominacion' => ' Deterioro de valor de propiedad industrial',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            196 => 
            array (
                'id' => 198,
                'cuenta' => '2905',
                'denominacion' => ' Deterioro de valor de derechos de traspaso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            197 => 
            array (
                'id' => 199,
                'cuenta' => '2906',
                'denominacion' => ' Deterioro de valor de aplicaciones informáticas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            198 => 
            array (
                'id' => 200,
                'cuenta' => '291',
                'denominacion' => ' Deterioro de valor del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            199 => 
            array (
                'id' => 201,
                'cuenta' => '2910',
                'denominacion' => ' Deterioro de valor de terrenos y bienes naturales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            200 => 
            array (
                'id' => 202,
                'cuenta' => '2911',
                'denominacion' => ' Deterioro de valor de construcciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            201 => 
            array (
                'id' => 203,
                'cuenta' => '2912',
                'denominacion' => ' Deterioro de valor de instalaciones técnicas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            202 => 
            array (
                'id' => 204,
                'cuenta' => '2913',
                'denominacion' => ' Deterioro de valor de maquinaria',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            203 => 
            array (
                'id' => 205,
                'cuenta' => '2914',
                'denominacion' => ' Deterioro de valor de utillaje',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            204 => 
            array (
                'id' => 206,
                'cuenta' => '2915',
                'denominacion' => ' Deterioro de valor de otras instalaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            205 => 
            array (
                'id' => 207,
                'cuenta' => '2916',
                'denominacion' => ' Deterioro de valor de mobiliario',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            206 => 
            array (
                'id' => 208,
                'cuenta' => '2917',
                'denominacion' => ' Deterioro de valor de equipos para procesos de información',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            207 => 
            array (
                'id' => 209,
                'cuenta' => '2918',
                'denominacion' => ' Deterioro de valor de elementos de transporte',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            208 => 
            array (
                'id' => 210,
                'cuenta' => '2919',
                'denominacion' => ' Deterioro de valor de otro inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            209 => 
            array (
                'id' => 211,
                'cuenta' => '292',
                'denominacion' => ' Deterioro de valor de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            210 => 
            array (
                'id' => 212,
                'cuenta' => '2920',
                'denominacion' => ' Deterioro de valor de los terrenos y bienes naturales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            211 => 
            array (
                'id' => 213,
                'cuenta' => '2921',
                'denominacion' => ' Deterioro de valor de construcciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            212 => 
            array (
                'id' => 214,
                'cuenta' => '293',
                'denominacion' => ' Deterioro de valor de participaciones a largo plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            213 => 
            array (
                'id' => 215,
                'cuenta' => '2933',
                'denominacion' => ' Deterioro de valor de participaciones a largo plazo en empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            214 => 
            array (
                'id' => 216,
                'cuenta' => '2934',
                'denominacion' => ' Deterioro de valor de participaciones a largo plazo en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            215 => 
            array (
                'id' => 217,
                'cuenta' => '294',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a largo plazo de partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            216 => 
            array (
                'id' => 218,
                'cuenta' => '2943',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a largo plazo de empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            217 => 
            array (
                'id' => 219,
                'cuenta' => '2944',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a largo plazo de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            218 => 
            array (
                'id' => 220,
                'cuenta' => '2945',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a largo plazo de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            219 => 
            array (
                'id' => 221,
                'cuenta' => '295',
                'denominacion' => ' Deterioro de valor de créditos a lar go plazo a partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            220 => 
            array (
                'id' => 222,
                'cuenta' => '2953',
                'denominacion' => ' Deterioro de valor de créditos a largo plazo a empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            221 => 
            array (
                'id' => 223,
                'cuenta' => '2954',
                'denominacion' => ' Deterioro de valor de créditos a largo plazo a empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            222 => 
            array (
                'id' => 224,
                'cuenta' => '2955',
                'denominacion' => ' Deterioro de valor de créditos a largo plazo a otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            223 => 
            array (
                'id' => 225,
                'cuenta' => '297',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            224 => 
            array (
                'id' => 226,
                'cuenta' => '298',
                'denominacion' => ' Deterioro de valor de créditos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            225 => 
            array (
                'id' => 227,
                'cuenta' => '3',
                'denominacion' => 'EXISTENCIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            226 => 
            array (
                'id' => 228,
                'cuenta' => '30',
                'denominacion' => ' COMERCIALES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            227 => 
            array (
                'id' => 229,
                'cuenta' => '300',
                'denominacion' => ' Mercaderías A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            228 => 
            array (
                'id' => 230,
                'cuenta' => '301',
                'denominacion' => ' Mercaderías B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            229 => 
            array (
                'id' => 231,
                'cuenta' => '31',
                'denominacion' => ' MATERIAS PRIMAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            230 => 
            array (
                'id' => 232,
                'cuenta' => '310',
                'denominacion' => ' Materias primas A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            231 => 
            array (
                'id' => 233,
                'cuenta' => '311',
                'denominacion' => ' Materias primas B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            232 => 
            array (
                'id' => 234,
                'cuenta' => '32',
                'denominacion' => ' OTROS APROVISIONAMIENTOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            233 => 
            array (
                'id' => 235,
                'cuenta' => '320',
                'denominacion' => ' Elementos y conjuntos incorporables',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            234 => 
            array (
                'id' => 236,
                'cuenta' => '321',
                'denominacion' => ' Combustibles',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            235 => 
            array (
                'id' => 237,
                'cuenta' => '322',
                'denominacion' => ' Repuestos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            236 => 
            array (
                'id' => 238,
                'cuenta' => '325',
                'denominacion' => ' Materiales diversos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            237 => 
            array (
                'id' => 239,
                'cuenta' => '326',
                'denominacion' => ' Embalajes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            238 => 
            array (
                'id' => 240,
                'cuenta' => '327',
                'denominacion' => ' Envases',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            239 => 
            array (
                'id' => 241,
                'cuenta' => '328',
                'denominacion' => ' Material de oficina',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            240 => 
            array (
                'id' => 242,
                'cuenta' => '33',
                'denominacion' => ' PRODUCTOS EN CURSO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            241 => 
            array (
                'id' => 243,
                'cuenta' => '330',
                'denominacion' => ' Productos en curso A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            242 => 
            array (
                'id' => 244,
                'cuenta' => '331',
                'denominacion' => ' Productos en curso B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            243 => 
            array (
                'id' => 245,
                'cuenta' => '34',
                'denominacion' => ' PRODUCTOS SEMITERMINADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            244 => 
            array (
                'id' => 246,
                'cuenta' => '340',
                'denominacion' => ' Productos semiterminados A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            245 => 
            array (
                'id' => 247,
                'cuenta' => '341',
                'denominacion' => ' Productos semiterminados B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            246 => 
            array (
                'id' => 248,
                'cuenta' => '35',
                'denominacion' => ' PRODUCTOS TERMINADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            247 => 
            array (
                'id' => 249,
                'cuenta' => '350',
                'denominacion' => ' Productos terminados A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            248 => 
            array (
                'id' => 250,
                'cuenta' => '351',
                'denominacion' => ' Productos terminados B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            249 => 
            array (
                'id' => 251,
                'cuenta' => '36',
                'denominacion' => ' SUBPRODUCTOS, RESIDUOS Y MATERIALES RECUPERADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            250 => 
            array (
                'id' => 252,
                'cuenta' => '360',
                'denominacion' => ' Subproductos A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            251 => 
            array (
                'id' => 253,
                'cuenta' => '361',
                'denominacion' => ' Subproductos B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            252 => 
            array (
                'id' => 254,
                'cuenta' => '365',
                'denominacion' => ' Residuos A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            253 => 
            array (
                'id' => 255,
                'cuenta' => '366',
                'denominacion' => ' Residuos B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            254 => 
            array (
                'id' => 256,
                'cuenta' => '368',
                'denominacion' => ' Materiales recuperados A',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            255 => 
            array (
                'id' => 257,
                'cuenta' => '369',
                'denominacion' => ' Materiales recuperados B',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            256 => 
            array (
                'id' => 258,
                'cuenta' => '39',
                'denominacion' => ' DETERIORO DE VALOR DE LAS EXISTENCIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            257 => 
            array (
                'id' => 259,
                'cuenta' => '390',
                'denominacion' => ' Deterioro de valor de las mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            258 => 
            array (
                'id' => 260,
                'cuenta' => '391',
                'denominacion' => ' Deterioro de valor de las materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            259 => 
            array (
                'id' => 261,
                'cuenta' => '392',
                'denominacion' => ' Deterioro de valor de otros aprovi sionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            260 => 
            array (
                'id' => 262,
                'cuenta' => '393',
                'denominacion' => ' Deterioro de valor de los productos en curso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            261 => 
            array (
                'id' => 263,
                'cuenta' => '394',
                'denominacion' => ' Deterioro de valor de los productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            262 => 
            array (
                'id' => 264,
                'cuenta' => '395',
                'denominacion' => ' Deterioro de valor de los productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            263 => 
            array (
                'id' => 265,
                'cuenta' => '396',
                'denominacion' => ' Deterioro de valor de los subproductos, residuos y materiales recuperados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            264 => 
            array (
                'id' => 266,
                'cuenta' => '4',
                'denominacion' => 'ACREEDORES Y DEUDORES POR OPERACIONES COMERCIALES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            265 => 
            array (
                'id' => 267,
                'cuenta' => '40',
                'denominacion' => ' PROVEEDORES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            266 => 
            array (
                'id' => 268,
                'cuenta' => '400',
                'denominacion' => ' Proveedores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            267 => 
            array (
                'id' => 269,
                'cuenta' => '4000',
            'denominacion' => ' Proveedores (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            268 => 
            array (
                'id' => 270,
                'cuenta' => '4004',
            'denominacion' => ' Proveedores (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            269 => 
            array (
                'id' => 271,
                'cuenta' => '4009',
                'denominacion' => ' Proveedores, facturas pendientes de recibir o de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            270 => 
            array (
                'id' => 272,
                'cuenta' => '401',
                'denominacion' => ' Proveedores, efectos comerciales a pagar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            271 => 
            array (
                'id' => 273,
                'cuenta' => '403',
                'denominacion' => ' Proveedores, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            272 => 
            array (
                'id' => 274,
                'cuenta' => '4030',
            'denominacion' => ' Proveedores, empresas del grupo (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            273 => 
            array (
                'id' => 275,
                'cuenta' => '4031',
                'denominacion' => ' Efectos comerciales a pagar, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            274 => 
            array (
                'id' => 276,
                'cuenta' => '4034',
            'denominacion' => ' Proveedores, empresas del grupo (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            275 => 
            array (
                'id' => 277,
                'cuenta' => '4036',
                'denominacion' => ' Envases y embalajes a devolver a proveedores, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            276 => 
            array (
                'id' => 278,
                'cuenta' => '4039',
                'denominacion' => ' Proveedores, empresas del grupo, facturas pendientes de recibir o de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            277 => 
            array (
                'id' => 279,
                'cuenta' => '404',
                'denominacion' => ' Proveedores, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            278 => 
            array (
                'id' => 280,
                'cuenta' => '405',
                'denominacion' => ' Proveedores, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            279 => 
            array (
                'id' => 281,
                'cuenta' => '406',
                'denominacion' => ' Envases y embalajes a devolver a proveedores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            280 => 
            array (
                'id' => 282,
                'cuenta' => '407',
                'denominacion' => ' Anticipos a proveedores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            281 => 
            array (
                'id' => 283,
                'cuenta' => '41',
                'denominacion' => ' ACREEDORES VARIOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            282 => 
            array (
                'id' => 284,
                'cuenta' => '410',
                'denominacion' => ' Acreedores por prestaciones de servicios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            283 => 
            array (
                'id' => 285,
                'cuenta' => '4100',
            'denominacion' => ' Acreedores por prestaciones de servicios (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            284 => 
            array (
                'id' => 286,
                'cuenta' => '4104',
            'denominacion' => ' Acreedores por prestaciones de servicios, (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            285 => 
            array (
                'id' => 287,
                'cuenta' => '4109',
                'denominacion' => ' Acreedores por prestaciones de servicios, facturas pendientes de recibir o de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            286 => 
            array (
                'id' => 288,
                'cuenta' => '411',
                'denominacion' => ' Acreedores, efectos comerciales a pagar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            287 => 
            array (
                'id' => 289,
                'cuenta' => '419',
                'denominacion' => ' Acreedores por operaciones en común',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            288 => 
            array (
                'id' => 290,
                'cuenta' => '43',
                'denominacion' => ' CLIENTES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            289 => 
            array (
                'id' => 291,
                'cuenta' => '430',
                'denominacion' => ' Clientes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            290 => 
            array (
                'id' => 292,
                'cuenta' => '4300',
            'denominacion' => ' Clientes (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            291 => 
            array (
                'id' => 293,
                'cuenta' => '4304',
            'denominacion' => ' Clientes (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            292 => 
            array (
                'id' => 294,
                'cuenta' => '4309',
                'denominacion' => ' Clientes, facturas pendientes de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            293 => 
            array (
                'id' => 295,
                'cuenta' => '431',
                'denominacion' => ' Clientes, efectos comerciales a cobrar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            294 => 
            array (
                'id' => 296,
                'cuenta' => '4310',
                'denominacion' => ' Efectos comerciales en cartera',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            295 => 
            array (
                'id' => 297,
                'cuenta' => '4311',
                'denominacion' => ' Efectos comerciales descontados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            296 => 
            array (
                'id' => 298,
                'cuenta' => '4312',
                'denominacion' => ' Efectos comerciales en gestión de cobro',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            297 => 
            array (
                'id' => 299,
                'cuenta' => '4315',
                'denominacion' => ' Efectos comerciales impagados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            298 => 
            array (
                'id' => 300,
                'cuenta' => '432',
                'denominacion' => ' Clientes, operaciones de “factoring”',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            299 => 
            array (
                'id' => 301,
                'cuenta' => '433',
                'denominacion' => ' Clientes, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            300 => 
            array (
                'id' => 302,
                'cuenta' => '4330',
            'denominacion' => ' Clientes empresas del grupo (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            301 => 
            array (
                'id' => 303,
                'cuenta' => '4331',
                'denominacion' => ' Efectos comerciales a cobrar, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            302 => 
            array (
                'id' => 304,
                'cuenta' => '4332',
                'denominacion' => ' Clientes empresas del grupo, operaciones de “factoring”',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            303 => 
            array (
                'id' => 305,
                'cuenta' => '4334',
            'denominacion' => ' Clientes empresas del grupo (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            304 => 
            array (
                'id' => 306,
                'cuenta' => '4336',
                'denominacion' => ' Clientes empresas del grupo de dudoso cobro',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            305 => 
            array (
                'id' => 307,
                'cuenta' => '4337',
                'denominacion' => ' Envases y embalajes a devolver a clientes, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            306 => 
            array (
                'id' => 308,
                'cuenta' => '4339',
                'denominacion' => ' Clientes empresas del grupo, facturas pendientes de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            307 => 
            array (
                'id' => 309,
                'cuenta' => '434',
                'denominacion' => ' Clientes, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            308 => 
            array (
                'id' => 310,
                'cuenta' => '435',
                'denominacion' => ' Clientes, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            309 => 
            array (
                'id' => 311,
                'cuenta' => '436',
                'denominacion' => ' Clientes de dudoso cobro',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            310 => 
            array (
                'id' => 312,
                'cuenta' => '437',
                'denominacion' => ' Envases y embalajes a devolver por clientes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            311 => 
            array (
                'id' => 313,
                'cuenta' => '438',
                'denominacion' => ' Anticipos de clientes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            312 => 
            array (
                'id' => 314,
                'cuenta' => '44',
                'denominacion' => ' DEUDORES VARIOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            313 => 
            array (
                'id' => 315,
                'cuenta' => '440',
                'denominacion' => ' Deudores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            314 => 
            array (
                'id' => 316,
                'cuenta' => '4400',
            'denominacion' => ' Deudores (euros)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            315 => 
            array (
                'id' => 317,
                'cuenta' => '4404',
            'denominacion' => ' Deudores (moneda extranjera)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            316 => 
            array (
                'id' => 318,
                'cuenta' => '4409',
                'denominacion' => ' Deudores, facturas pendientes de formalizar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            317 => 
            array (
                'id' => 319,
                'cuenta' => '441',
                'denominacion' => ' Deudores, efectos comerciales a cobrar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            318 => 
            array (
                'id' => 320,
                'cuenta' => '4410',
                'denominacion' => ' Deudores, efectos comerciales en cartera',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            319 => 
            array (
                'id' => 321,
                'cuenta' => '4411',
                'denominacion' => ' Deudores, efectos comerciales descontados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            320 => 
            array (
                'id' => 322,
                'cuenta' => '4412',
                'denominacion' => ' Deudores, efectos comerciales en gestión de cobro',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            321 => 
            array (
                'id' => 323,
                'cuenta' => '4415',
                'denominacion' => ' Deudores, efectos comerciales impagados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            322 => 
            array (
                'id' => 324,
                'cuenta' => '446',
                'denominacion' => ' Deudores de dudoso cobro',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            323 => 
            array (
                'id' => 325,
                'cuenta' => '449',
                'denominacion' => ' Deudores por operaciones en común',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            324 => 
            array (
                'id' => 326,
                'cuenta' => '46',
                'denominacion' => ' PERSONAL',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            325 => 
            array (
                'id' => 327,
                'cuenta' => '460',
                'denominacion' => ' Anticipos de remuneraciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            326 => 
            array (
                'id' => 328,
                'cuenta' => '465',
                'denominacion' => ' Remuneraciones pendientes de pago',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            327 => 
            array (
                'id' => 329,
                'cuenta' => '466',
                'denominacion' => ' Remuneraciones mediante sistemas de aportación definida pendientes de pago',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            328 => 
            array (
                'id' => 330,
                'cuenta' => '47',
                'denominacion' => ' ADMINISTRACIONES PÚBLICAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            329 => 
            array (
                'id' => 331,
                'cuenta' => '470',
                'denominacion' => ' Hacienda Pública, deudora por diversos conceptos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            330 => 
            array (
                'id' => 332,
                'cuenta' => '4700',
                'denominacion' => ' Hacienda Pública, deudora por IVA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            331 => 
            array (
                'id' => 333,
                'cuenta' => '4708',
                'denominacion' => ' Hacienda Pública, deudora por subvenciones concedidas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            332 => 
            array (
                'id' => 334,
                'cuenta' => '4709',
                'denominacion' => ' Hacienda Pública, deudora por devolución de impuestos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            333 => 
            array (
                'id' => 335,
                'cuenta' => '471',
                'denominacion' => ' Organismos de la Seguridad Social, deudores ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            334 => 
            array (
                'id' => 336,
                'cuenta' => '472',
                'denominacion' => ' Hacienda Pública, IVA soportado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            335 => 
            array (
                'id' => 337,
                'cuenta' => '473',
                'denominacion' => ' Hacienda Pública, retenciones y pagos a cuenta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            336 => 
            array (
                'id' => 338,
                'cuenta' => '474',
                'denominacion' => ' Activos por impuesto diferido',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            337 => 
            array (
                'id' => 339,
                'cuenta' => '4740',
                'denominacion' => ' Activos por diferencias temporarias deducibles',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            338 => 
            array (
                'id' => 340,
                'cuenta' => '4742',
                'denominacion' => ' Derechos por deducciones y bonificaciones pendientes de aplicar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            339 => 
            array (
                'id' => 341,
                'cuenta' => '4745',
                'denominacion' => ' Crédito por pérdidas a compensar del ejercicio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            340 => 
            array (
                'id' => 342,
                'cuenta' => '475',
                'denominacion' => ' Hacienda Pública, acreedora por conceptos fiscales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            341 => 
            array (
                'id' => 343,
                'cuenta' => '4750',
                'denominacion' => ' Hacienda Pública, acreedora por IVA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            342 => 
            array (
                'id' => 344,
                'cuenta' => '4751',
                'denominacion' => ' Hacienda Pública, acreedora por retenciones practicadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            343 => 
            array (
                'id' => 345,
                'cuenta' => '4752',
                'denominacion' => ' Hacienda Pública, acreedora por impuesto sobre sociedades',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            344 => 
            array (
                'id' => 346,
                'cuenta' => '4758',
                'denominacion' => ' Hacienda Pública, acreedora por subvenciones a reintegrar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            345 => 
            array (
                'id' => 347,
                'cuenta' => '476',
                'denominacion' => ' Organismos de la Seguridad Social, acreedores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            346 => 
            array (
                'id' => 348,
                'cuenta' => '477',
                'denominacion' => ' Hacienda Pública, IVA repercutido',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            347 => 
            array (
                'id' => 349,
                'cuenta' => '479',
                'denominacion' => ' Pasivos por diferencias temporarias imponibles',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            348 => 
            array (
                'id' => 350,
                'cuenta' => '48',
                'denominacion' => ' AJUSTES POR PERIODIFICACIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            349 => 
            array (
                'id' => 351,
                'cuenta' => '480',
                'denominacion' => ' Gastos anticipados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            350 => 
            array (
                'id' => 352,
                'cuenta' => '485',
                'denominacion' => ' Ingresos anticipados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            351 => 
            array (
                'id' => 353,
                'cuenta' => '49',
                'denominacion' => ' DETERIORO DE VALOR DE CRÉDITOS COMERCIALES Y PROVISIONES A CORTO PLAZO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            352 => 
            array (
                'id' => 354,
                'cuenta' => '490',
                'denominacion' => ' Deterioro de valor de créditos por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            353 => 
            array (
                'id' => 355,
                'cuenta' => '493',
                'denominacion' => ' Deterioro de valor de créditos por operaciones comerciales con partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            354 => 
            array (
                'id' => 356,
                'cuenta' => '4933',
                'denominacion' => ' Deterioro de valor de créditos por operaciones comerciales con empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            355 => 
            array (
                'id' => 357,
                'cuenta' => '4934',
                'denominacion' => ' Deterioro de valor de créditos por operaciones comerciales con empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            356 => 
            array (
                'id' => 358,
                'cuenta' => '4935',
                'denominacion' => ' Deterioro de valor de créditos por operaciones comerciales con otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            357 => 
            array (
                'id' => 359,
                'cuenta' => '499',
                'denominacion' => ' Provisiones por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            358 => 
            array (
                'id' => 360,
                'cuenta' => '4994',
                'denominacion' => ' Provisión por contratos onerosos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            359 => 
            array (
                'id' => 361,
                'cuenta' => '4999',
                'denominacion' => ' Provisión para otras operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            360 => 
            array (
                'id' => 362,
                'cuenta' => '5',
                'denominacion' => 'CUENTAS FINANCIERAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            361 => 
            array (
                'id' => 363,
                'cuenta' => '50',
                'denominacion' => ' EMPRÉSTITOS, DEUDAS CON CARÁCTERÍSTICAS ESPECIALES Y OTRAS EMISIONES ANÁLOGAS A CORTO PLAZO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            362 => 
            array (
                'id' => 364,
                'cuenta' => '500',
                'denominacion' => ' Obligaciones y bonos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            363 => 
            array (
                'id' => 365,
                'cuenta' => '501',
                'denominacion' => ' Obligaciones y bonos convertibles a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            364 => 
            array (
                'id' => 366,
                'cuenta' => '502',
                'denominacion' => ' Acciones o participaciones a corto plazo consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            365 => 
            array (
                'id' => 367,
                'cuenta' => '505',
                'denominacion' => ' Deudas representadas en otros valores negociables a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            366 => 
            array (
                'id' => 368,
                'cuenta' => '506',
                'denominacion' => ' Intereses a corto plazo de empréstitos y otras emisiones análogas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            367 => 
            array (
                'id' => 369,
                'cuenta' => '507',
                'denominacion' => ' Dividendos de acciones o participaciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            368 => 
            array (
                'id' => 370,
                'cuenta' => '509',
                'denominacion' => ' Valores negociables amortizados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            369 => 
            array (
                'id' => 371,
                'cuenta' => '5090',
                'denominacion' => ' Obligaciones y bonos amortizados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            370 => 
            array (
                'id' => 372,
                'cuenta' => '5091',
                'denominacion' => ' Obligaciones y bonos convertibles amortizados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            371 => 
            array (
                'id' => 373,
                'cuenta' => '5095',
                'denominacion' => ' Otros valores negociables amortizados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            372 => 
            array (
                'id' => 374,
                'cuenta' => '51',
                'denominacion' => ' DEUDAS A CORTO PLAZO CON PARTES VINCULADAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            373 => 
            array (
                'id' => 375,
                'cuenta' => '510',
                'denominacion' => ' Deudas a corto plazo con entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            374 => 
            array (
                'id' => 376,
                'cuenta' => '5103',
                'denominacion' => ' Deudas a corto plazo con entidades de crédito, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            375 => 
            array (
                'id' => 377,
                'cuenta' => '5104',
                'denominacion' => ' Deudas a corto plazo con entidades de crédito, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            376 => 
            array (
                'id' => 378,
                'cuenta' => '5105',
                'denominacion' => ' Deudas a corto plazo con otras entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            377 => 
            array (
                'id' => 379,
                'cuenta' => '511',
                'denominacion' => ' Proveedores de inmovilizado a corto plazo, partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            378 => 
            array (
                'id' => 380,
                'cuenta' => '5113',
                'denominacion' => ' Proveedores de inmovilizado a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            379 => 
            array (
                'id' => 381,
                'cuenta' => '5114',
                'denominacion' => ' Proveedores de inmovilizado a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            380 => 
            array (
                'id' => 382,
                'cuenta' => '5115',
                'denominacion' => ' Proveedores de inmovilizado a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            381 => 
            array (
                'id' => 383,
                'cuenta' => '512',
                'denominacion' => ' Acreedores por arrendamiento financiero a corto plazo, partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            382 => 
            array (
                'id' => 384,
                'cuenta' => '5123',
                'denominacion' => ' Acreedores por arrendamiento financiero a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            383 => 
            array (
                'id' => 385,
                'cuenta' => '5124',
                'denominacion' => ' Acreedores por arrendamiento financiero a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            384 => 
            array (
                'id' => 386,
                'cuenta' => '5125',
                'denominacion' => ' Acreedores por arrendamiento financiero a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            385 => 
            array (
                'id' => 387,
                'cuenta' => '513',
                'denominacion' => ' Otras deudas a corto plazo con partes vinculadas ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            386 => 
            array (
                'id' => 388,
                'cuenta' => '5133',
                'denominacion' => ' Otras deudas a corto plazo con empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            387 => 
            array (
                'id' => 389,
                'cuenta' => '5134',
                'denominacion' => ' Otras deudas a corto plazo con empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            388 => 
            array (
                'id' => 390,
                'cuenta' => '5135',
                'denominacion' => ' Otras deudas a corto plazo con otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            389 => 
            array (
                'id' => 391,
                'cuenta' => '514',
                'denominacion' => ' Intereses a corto plazo de deudas con partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            390 => 
            array (
                'id' => 392,
                'cuenta' => '5143',
                'denominacion' => ' Intereses a corto plazo de deudas, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            391 => 
            array (
                'id' => 393,
                'cuenta' => '5144',
                'denominacion' => ' Intereses a corto plazo de deudas, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            392 => 
            array (
                'id' => 394,
                'cuenta' => '5145',
                'denominacion' => ' Intereses a corto plazo de deudas, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            393 => 
            array (
                'id' => 395,
                'cuenta' => '52',
                'denominacion' => ' DEUDAS A CORTO PLAZO POR PRÉSTAMOS RECIBIDOS Y OTROS CONCEPTOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            394 => 
            array (
                'id' => 396,
                'cuenta' => '520',
                'denominacion' => ' Deudas a corto plazo con entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            395 => 
            array (
                'id' => 397,
                'cuenta' => '5200',
                'denominacion' => ' Préstamos a corto plazo de entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            396 => 
            array (
                'id' => 398,
                'cuenta' => '5201',
                'denominacion' => ' Deudas a corto plazo por crédito dispuesto',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            397 => 
            array (
                'id' => 399,
                'cuenta' => '5208',
                'denominacion' => ' Deudas por efectos descontados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            398 => 
            array (
                'id' => 400,
                'cuenta' => '5209',
                'denominacion' => ' Deudas por operaciones de “factoring”',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            399 => 
            array (
                'id' => 401,
                'cuenta' => '521',
                'denominacion' => ' Deudas a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            400 => 
            array (
                'id' => 402,
                'cuenta' => '522',
                'denominacion' => ' Deudas a corto plazo transformables en subvenciones, donaciones y legados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            401 => 
            array (
                'id' => 403,
                'cuenta' => '523',
                'denominacion' => ' Proveedores de inmovilizado a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            402 => 
            array (
                'id' => 404,
                'cuenta' => '524',
                'denominacion' => ' Acreedores por arrendamiento financiero a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            403 => 
            array (
                'id' => 405,
                'cuenta' => '525',
                'denominacion' => ' Efectos a pagar a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            404 => 
            array (
                'id' => 406,
                'cuenta' => '526',
                'denominacion' => ' Dividendo activo a pagar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            405 => 
            array (
                'id' => 407,
                'cuenta' => '527',
                'denominacion' => ' Intereses a corto plazo de deudas con entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            406 => 
            array (
                'id' => 408,
                'cuenta' => '528',
                'denominacion' => ' Intereses a corto plazo de deudas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            407 => 
            array (
                'id' => 409,
                'cuenta' => '529',
                'denominacion' => ' Provisiones a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            408 => 
            array (
                'id' => 410,
                'cuenta' => '5290',
                'denominacion' => ' Provisión a corto plazo por retribuciones al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            409 => 
            array (
                'id' => 411,
                'cuenta' => '5291',
                'denominacion' => ' Provisión a corto plazo para impuestos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            410 => 
            array (
                'id' => 412,
                'cuenta' => '5292',
                'denominacion' => ' Provisión a corto plazo para otras responsabilidades',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            411 => 
            array (
                'id' => 413,
                'cuenta' => '5293',
                'denominacion' => ' Provisión a corto plazo por desmantelamiento, retiro o rehabilitación del inmovilizado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            412 => 
            array (
                'id' => 414,
                'cuenta' => '5295',
                'denominacion' => ' Provisión a corto plazo para actuaciones medioambientales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            413 => 
            array (
                'id' => 415,
                'cuenta' => '5296',
                'denominacion' => ' Provisión a corto plazo para reestructuraciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            414 => 
            array (
                'id' => 416,
                'cuenta' => '5297',
                'denominacion' => ' Provisión a corto plazo por transacciones con pagos basados en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            415 => 
            array (
                'id' => 417,
                'cuenta' => '53',
                'denominacion' => ' INVERSIONES FINANCIERAS A CORTO PLAZO EN PARTES VINCULADAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            416 => 
            array (
                'id' => 418,
                'cuenta' => '530',
                'denominacion' => ' Participaciones a corto plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            417 => 
            array (
                'id' => 419,
                'cuenta' => '5303',
                'denominacion' => ' Participaciones a corto plazo, en empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            418 => 
            array (
                'id' => 420,
                'cuenta' => '5304',
                'denominacion' => ' Participaciones a corto plazo, en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            419 => 
            array (
                'id' => 421,
                'cuenta' => '5305',
                'denominacion' => ' Participaciones a corto plazo, en otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            420 => 
            array (
                'id' => 422,
                'cuenta' => '531',
                'denominacion' => ' Valores representativos de deuda a corto plazo de partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            421 => 
            array (
                'id' => 423,
                'cuenta' => '5313',
                'denominacion' => ' Valores representativos de deuda a corto plazo de empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            422 => 
            array (
                'id' => 424,
                'cuenta' => '5314',
                'denominacion' => ' Valores representativos de deuda a corto plazo de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            423 => 
            array (
                'id' => 425,
                'cuenta' => '5315',
                'denominacion' => ' Valores representativos de deuda a corto plazo de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            424 => 
            array (
                'id' => 426,
                'cuenta' => '532',
                'denominacion' => ' Créditos a corto plazo a partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            425 => 
            array (
                'id' => 427,
                'cuenta' => '5323',
                'denominacion' => ' Créditos a corto plazo a empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            426 => 
            array (
                'id' => 428,
                'cuenta' => '5324',
                'denominacion' => ' Créditos a corto plazo a empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            427 => 
            array (
                'id' => 429,
                'cuenta' => '5325',
                'denominacion' => ' Créditos a corto plazo a otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            428 => 
            array (
                'id' => 430,
                'cuenta' => '533',
                'denominacion' => ' Intereses a corto plazo de valores representativos de deuda de partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            429 => 
            array (
                'id' => 431,
                'cuenta' => '5333',
                'denominacion' => ' Intereses a corto plazo de valores representativos de deuda de empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            430 => 
            array (
                'id' => 432,
                'cuenta' => '5334',
                'denominacion' => ' Intereses a corto plazo de valores representativos de deuda de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            431 => 
            array (
                'id' => 433,
                'cuenta' => '5335',
                'denominacion' => ' Intereses a corto plazo de valores representativos de deuda de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            432 => 
            array (
                'id' => 434,
                'cuenta' => '534',
                'denominacion' => ' Intereses a corto plazo de créditos a partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            433 => 
            array (
                'id' => 435,
                'cuenta' => '5343',
                'denominacion' => ' Intereses a corto plazo de créditos a empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            434 => 
            array (
                'id' => 436,
                'cuenta' => '5344',
                'denominacion' => ' Intereses a corto plazo de créditos a empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            435 => 
            array (
                'id' => 437,
                'cuenta' => '5345',
                'denominacion' => ' Intereses a corto plazo de créditos a otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            436 => 
            array (
                'id' => 438,
                'cuenta' => '535',
                'denominacion' => ' Dividendo a cobrar de inversiones financieras en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            437 => 
            array (
                'id' => 439,
                'cuenta' => '5353',
                'denominacion' => ' Dividendo a cobrar de empresas de grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            438 => 
            array (
                'id' => 440,
                'cuenta' => '5354',
                'denominacion' => ' Dividendo a cobrar de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            439 => 
            array (
                'id' => 441,
                'cuenta' => '5355',
                'denominacion' => ' Dividendo a cobrar de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            440 => 
            array (
                'id' => 442,
                'cuenta' => '539',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a corto plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            441 => 
            array (
                'id' => 443,
                'cuenta' => '5393',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a corto plazo en empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            442 => 
            array (
                'id' => 444,
                'cuenta' => '5394',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a corto plazo en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            443 => 
            array (
                'id' => 445,
                'cuenta' => '5395',
                'denominacion' => ' Desembolsos pendientes sobre participaciones a corto plazo en otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            444 => 
            array (
                'id' => 446,
                'cuenta' => '54',
                'denominacion' => ' OTRAS INVERSIONES FINANCIERAS A CORTO PLAZO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            445 => 
            array (
                'id' => 447,
                'cuenta' => '540',
                'denominacion' => ' Inversiones financieras a corto plazo en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            446 => 
            array (
                'id' => 448,
                'cuenta' => '541',
                'denominacion' => ' Valores representativos de deuda a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            447 => 
            array (
                'id' => 449,
                'cuenta' => '542',
                'denominacion' => ' Créditos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            448 => 
            array (
                'id' => 450,
                'cuenta' => '543',
                'denominacion' => ' Créditos a corto plazo por enajenación de inmovilizado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            449 => 
            array (
                'id' => 451,
                'cuenta' => '544',
                'denominacion' => ' Créditos a corto plazo al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            450 => 
            array (
                'id' => 452,
                'cuenta' => '545',
                'denominacion' => ' Dividendo a cobrar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            451 => 
            array (
                'id' => 453,
                'cuenta' => '546',
                'denominacion' => ' Intereses a corto plazo de valores representativos de deudas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            452 => 
            array (
                'id' => 454,
                'cuenta' => '547',
                'denominacion' => ' Intereses a corto plazo de créditos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            453 => 
            array (
                'id' => 455,
                'cuenta' => '548',
                'denominacion' => ' Imposiciones a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            454 => 
            array (
                'id' => 456,
                'cuenta' => '549',
                'denominacion' => ' Desembolsos pendientes sobre participaciones en el patrimonio neto a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            455 => 
            array (
                'id' => 457,
                'cuenta' => '55',
                'denominacion' => ' OTRAS CUENTAS NO BANCARIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            456 => 
            array (
                'id' => 458,
                'cuenta' => '550',
                'denominacion' => ' Titular de la explotación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            457 => 
            array (
                'id' => 459,
                'cuenta' => '551',
                'denominacion' => ' Cuenta corriente con socios y administradores',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            458 => 
            array (
                'id' => 460,
                'cuenta' => '552',
                'denominacion' => ' Cuenta corriente con otras personas y entidades vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            459 => 
            array (
                'id' => 461,
                'cuenta' => '5523',
                'denominacion' => ' Cuenta corriente con empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            460 => 
            array (
                'id' => 462,
                'cuenta' => '5524',
                'denominacion' => ' Cuenta corriente con empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            461 => 
            array (
                'id' => 463,
                'cuenta' => '5525',
                'denominacion' => ' Cuenta corriente con otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            462 => 
            array (
                'id' => 464,
                'cuenta' => '553',
                'denominacion' => ' Cuentas corrientes en fusiones y escisiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            463 => 
            array (
                'id' => 465,
                'cuenta' => '5530',
                'denominacion' => ' Socios de sociedad disuelta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            464 => 
            array (
                'id' => 466,
                'cuenta' => '5531',
                'denominacion' => ' Socios, cuenta de fusión',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            465 => 
            array (
                'id' => 467,
                'cuenta' => '5532',
                'denominacion' => ' Socios de sociedad escindida',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            466 => 
            array (
                'id' => 468,
                'cuenta' => '5533',
                'denominacion' => ' Socios, cuenta de escisión',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            467 => 
            array (
                'id' => 469,
                'cuenta' => '554',
                'denominacion' => ' Cuenta corriente con uniones temporales de empresas y comunidades de bienes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            468 => 
            array (
                'id' => 470,
                'cuenta' => '555',
                'denominacion' => ' Partidas pendientes de aplicación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            469 => 
            array (
                'id' => 471,
                'cuenta' => '556',
                'denominacion' => ' Desembolsos exigidos sobre participaciones en el patrimonio neto',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            470 => 
            array (
                'id' => 472,
                'cuenta' => '5563',
                'denominacion' => ' Desembolsos exigidos sobre participaciones, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            471 => 
            array (
                'id' => 473,
                'cuenta' => '5564',
                'denominacion' => ' Desembolsos exigidos sobre participaciones, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            472 => 
            array (
                'id' => 474,
                'cuenta' => '5565',
                'denominacion' => ' Desembolsos exigidos sobre participaciones, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            473 => 
            array (
                'id' => 475,
                'cuenta' => '5566',
                'denominacion' => ' Desembolsos exigidos sobre participaciones de otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            474 => 
            array (
                'id' => 476,
                'cuenta' => '557',
                'denominacion' => ' Dividendo activo a cuenta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            475 => 
            array (
                'id' => 477,
                'cuenta' => '558',
                'denominacion' => ' Socios por desembolsos exigidos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            476 => 
            array (
                'id' => 478,
                'cuenta' => '5580',
                'denominacion' => ' Socios por desembolsos exigidos sobre acciones o participaciones ordinarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            477 => 
            array (
                'id' => 479,
                'cuenta' => '5585',
                'denominacion' => ' Socios por desembolsos exigidos sobre acciones o participaciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            478 => 
            array (
                'id' => 480,
                'cuenta' => '559',
                'denominacion' => ' Derivados financieros a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            479 => 
            array (
                'id' => 481,
                'cuenta' => '5590',
                'denominacion' => ' Activos por derivados financieros a corto plazo, cartera de negociación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            480 => 
            array (
                'id' => 482,
                'cuenta' => '5593',
                'denominacion' => ' Activos por derivados financieros a corto plazo, instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            481 => 
            array (
                'id' => 483,
                'cuenta' => '5595',
                'denominacion' => ' Pasivos por derivados financieros a corto plazo, cartera de negociación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            482 => 
            array (
                'id' => 484,
                'cuenta' => '5598',
                'denominacion' => ' Pasivos por derivados financieros a corto plazo, instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            483 => 
            array (
                'id' => 485,
                'cuenta' => '56',
                'denominacion' => ' FIANZAS Y DEPÓSITOS RECIBIDOS Y CONSTITUIDOS A COR TO PLAZO Y AJUSTES POR PERIODIFICACIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            484 => 
            array (
                'id' => 486,
                'cuenta' => '560',
                'denominacion' => ' Fianzas recibidas a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            485 => 
            array (
                'id' => 487,
                'cuenta' => '561',
                'denominacion' => ' Depósitos recibidos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            486 => 
            array (
                'id' => 488,
                'cuenta' => '565',
                'denominacion' => ' Fianzas constituidas a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            487 => 
            array (
                'id' => 489,
                'cuenta' => '566',
                'denominacion' => ' Depósitos constituidos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            488 => 
            array (
                'id' => 490,
                'cuenta' => '567',
                'denominacion' => ' Intereses pagados por anticipado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            489 => 
            array (
                'id' => 491,
                'cuenta' => '568',
                'denominacion' => ' Intereses cobrados por anticipado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            490 => 
            array (
                'id' => 492,
                'cuenta' => '569',
                'denominacion' => ' Garantías financieras a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            491 => 
            array (
                'id' => 493,
                'cuenta' => '57',
                'denominacion' => ' TESORERÍA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            492 => 
            array (
                'id' => 494,
                'cuenta' => '570',
                'denominacion' => ' Caja, euros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            493 => 
            array (
                'id' => 495,
                'cuenta' => '571',
                'denominacion' => ' Caja, moneda extranjera',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            494 => 
            array (
                'id' => 496,
                'cuenta' => '572',
                'denominacion' => ' Bancos e instituciones de crédito c/c vista, euros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            495 => 
            array (
                'id' => 497,
                'cuenta' => '573',
                'denominacion' => ' Bancos e instituciones de crédito c/c vista, moneda extranjera',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            496 => 
            array (
                'id' => 498,
                'cuenta' => '574',
                'denominacion' => ' Bancos e instituciones de crédito, cuentas de ahorro, euros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            497 => 
            array (
                'id' => 499,
                'cuenta' => '575',
                'denominacion' => ' Bancos e instituciones de crédito, cuentas de ahorro, moneda extranjera',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            498 => 
            array (
                'id' => 500,
                'cuenta' => '576',
                'denominacion' => ' Inversiones a corto plazo de gran liquidez',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            499 => 
            array (
                'id' => 501,
                'cuenta' => '58',
                'denominacion' => ' ACTIVOS NO CORRIENTES MANTENIDOS PARA LA VENTA Y ACTIVOS Y PASIVOS ASOCIADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
        ));
        \DB::table('categoria_cuenta_contable')->insert(array (
            0 => 
            array (
                'id' => 502,
                'cuenta' => '580',
                'denominacion' => ' Inmovilizado',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            1 => 
            array (
                'id' => 503,
                'cuenta' => '581',
                'denominacion' => ' Inversiones con personas y entidades vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            2 => 
            array (
                'id' => 504,
                'cuenta' => '582',
                'denominacion' => ' Inversiones financieras',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            3 => 
            array (
                'id' => 505,
                'cuenta' => '583',
                'denominacion' => ' Existencias, deudores comerciales y otras cuentas a cobrar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            4 => 
            array (
                'id' => 506,
                'cuenta' => '584',
                'denominacion' => ' Otros activos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            5 => 
            array (
                'id' => 507,
                'cuenta' => '585',
                'denominacion' => ' Provisiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            6 => 
            array (
                'id' => 508,
                'cuenta' => '586',
                'denominacion' => ' Deudas con características especiales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            7 => 
            array (
                'id' => 509,
                'cuenta' => '587',
                'denominacion' => ' Deudas con personas y entidades vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            8 => 
            array (
                'id' => 510,
                'cuenta' => '588',
                'denominacion' => ' Acreedores comerciales y otras cuentas a pagar',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            9 => 
            array (
                'id' => 511,
                'cuenta' => '589',
                'denominacion' => ' Otros pasivos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            10 => 
            array (
                'id' => 512,
                'cuenta' => '59',
                'denominacion' => ' DETERIORO DEL VALOR DE INVERSIONES FINANCIERAS A CORTO PLAZO Y DE ACTIVOS NO CORRIENTES MANTENIDOS PARA LA VENTA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            11 => 
            array (
                'id' => 513,
                'cuenta' => '593',
                'denominacion' => ' Deterioro de valor de participaciones a corto plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            12 => 
            array (
                'id' => 514,
                'cuenta' => '5933',
                'denominacion' => ' Deterioro de valor de participaciones a corto plazo en empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            13 => 
            array (
                'id' => 515,
                'cuenta' => '5934',
                'denominacion' => ' Deterioro de valor de participaciones a corto plazo en empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            14 => 
            array (
                'id' => 516,
                'cuenta' => '594',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a corto plazo de partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            15 => 
            array (
                'id' => 517,
                'cuenta' => '5943',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a corto plazo de empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            16 => 
            array (
                'id' => 518,
                'cuenta' => '5944',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a corto plazo de empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            17 => 
            array (
                'id' => 519,
                'cuenta' => '5945',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a corto plazo de otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            18 => 
            array (
                'id' => 520,
                'cuenta' => '595',
                'denominacion' => ' Deterioro de valor de créditos a corto plazo a partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            19 => 
            array (
                'id' => 521,
                'cuenta' => '5953',
                'denominacion' => ' Deterioro de valor de créditos a corto plazo a empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            20 => 
            array (
                'id' => 522,
                'cuenta' => '5954',
                'denominacion' => ' Deterioro de valor de créditos a corto plazo a empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            21 => 
            array (
                'id' => 523,
                'cuenta' => '5955',
                'denominacion' => ' Deterioro de valor de créditos a corto plazo a otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            22 => 
            array (
                'id' => 524,
                'cuenta' => '597',
                'denominacion' => ' Deterioro de valor de valores representativos de deuda a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            23 => 
            array (
                'id' => 525,
                'cuenta' => '598',
                'denominacion' => ' Deterioro de valor de créditos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            24 => 
            array (
                'id' => 526,
                'cuenta' => '599',
                'denominacion' => ' Deterioro de valor de activos no corrientes mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            25 => 
            array (
                'id' => 527,
                'cuenta' => '5990',
                'denominacion' => ' Deterioro de valor de inmovilizado no corriente mantenido para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            26 => 
            array (
                'id' => 528,
                'cuenta' => '5991',
                'denominacion' => ' Deterioro de valor de inversiones con personas y entidades vinculadas no corrientes mantenidas para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            27 => 
            array (
                'id' => 529,
                'cuenta' => '5992',
                'denominacion' => ' Deterioro de valor de inversiones financieras no corrientes mantenidas para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            28 => 
            array (
                'id' => 530,
                'cuenta' => '5993',
                'denominacion' => ' Deterioro de valor de existencias, deudores comerciales y otras cuentas a cobrar integrados en un grupo enajenable mantenido para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            29 => 
            array (
                'id' => 531,
                'cuenta' => '5994',
                'denominacion' => ' Deterioro de valor de otros activos mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            30 => 
            array (
                'id' => 532,
                'cuenta' => '6',
                'denominacion' => 'COMPRAS Y GASTOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            31 => 
            array (
                'id' => 533,
                'cuenta' => '60',
                'denominacion' => ' COMPRAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            32 => 
            array (
                'id' => 534,
                'cuenta' => '600',
                'denominacion' => ' Compras de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            33 => 
            array (
                'id' => 535,
                'cuenta' => '601',
                'denominacion' => ' Compras de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            34 => 
            array (
                'id' => 536,
                'cuenta' => '602',
                'denominacion' => ' Compras de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            35 => 
            array (
                'id' => 537,
                'cuenta' => '606',
                'denominacion' => ' Descuentos sobre compras por pronto pago',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            36 => 
            array (
                'id' => 538,
                'cuenta' => '6060',
                'denominacion' => ' Descuentos sobre compras por pronto pago de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            37 => 
            array (
                'id' => 539,
                'cuenta' => '6061',
                'denominacion' => ' Descuentos sobre compras por pronto pago de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            38 => 
            array (
                'id' => 540,
                'cuenta' => '6062',
                'denominacion' => ' Descuentos sobre compras por pronto pago de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            39 => 
            array (
                'id' => 541,
                'cuenta' => '607',
                'denominacion' => ' Trabajos realizados por otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            40 => 
            array (
                'id' => 542,
                'cuenta' => '608',
                'denominacion' => ' Devoluciones de compras y operaciones similares',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            41 => 
            array (
                'id' => 543,
                'cuenta' => '6080',
                'denominacion' => ' Devoluciones de compras de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            42 => 
            array (
                'id' => 544,
                'cuenta' => '6081',
                'denominacion' => ' Devoluciones de compras de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            43 => 
            array (
                'id' => 545,
                'cuenta' => '6082',
                'denominacion' => ' Devoluciones de compras de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            44 => 
            array (
                'id' => 546,
                'cuenta' => '609',
                'denominacion' => ' “Rappels” por compras',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            45 => 
            array (
                'id' => 547,
                'cuenta' => '6090',
                'denominacion' => ' “Rappels” por compras de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            46 => 
            array (
                'id' => 548,
                'cuenta' => '6091',
                'denominacion' => ' “Rappels” por compras de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            47 => 
            array (
                'id' => 549,
                'cuenta' => '6092',
                'denominacion' => ' “Rappels” por compras de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            48 => 
            array (
                'id' => 550,
                'cuenta' => '61',
                'denominacion' => ' VARIACIÓN DE EXISTENCIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            49 => 
            array (
                'id' => 551,
                'cuenta' => '610',
                'denominacion' => ' Variación de existencias de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            50 => 
            array (
                'id' => 552,
                'cuenta' => '611',
                'denominacion' => ' Variación de existencias de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            51 => 
            array (
                'id' => 553,
                'cuenta' => '612',
                'denominacion' => ' Variación de existencias de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            52 => 
            array (
                'id' => 554,
                'cuenta' => '62',
                'denominacion' => ' SERVICIOS EXTERIORES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            53 => 
            array (
                'id' => 555,
                'cuenta' => '620',
                'denominacion' => ' Gastos en investigación y desarrollo del ejercicio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            54 => 
            array (
                'id' => 556,
                'cuenta' => '621',
                'denominacion' => ' Arrendamientos y cánones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            55 => 
            array (
                'id' => 557,
                'cuenta' => '622',
                'denominacion' => ' Reparaciones y conservación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            56 => 
            array (
                'id' => 558,
                'cuenta' => '623',
                'denominacion' => ' Servicios de profesionales independientes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            57 => 
            array (
                'id' => 559,
                'cuenta' => '624',
                'denominacion' => ' Transportes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            58 => 
            array (
                'id' => 560,
                'cuenta' => '625',
                'denominacion' => ' Primas de seguros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            59 => 
            array (
                'id' => 561,
                'cuenta' => '626',
                'denominacion' => ' Servicios bancarios y similares',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            60 => 
            array (
                'id' => 562,
                'cuenta' => '627',
                'denominacion' => ' Publicidad, propaganda y relaciones públicas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            61 => 
            array (
                'id' => 563,
                'cuenta' => '628',
                'denominacion' => ' Suministros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            62 => 
            array (
                'id' => 564,
                'cuenta' => '629',
                'denominacion' => ' Otros servicios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            63 => 
            array (
                'id' => 565,
                'cuenta' => '63',
                'denominacion' => ' TRIBUTOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            64 => 
            array (
                'id' => 566,
                'cuenta' => '630',
                'denominacion' => ' Impuesto sobre beneficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            65 => 
            array (
                'id' => 567,
                'cuenta' => '6300',
                'denominacion' => ' Impuesto corriente',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            66 => 
            array (
                'id' => 568,
                'cuenta' => '6301',
                'denominacion' => ' Impuesto diferido',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            67 => 
            array (
                'id' => 569,
                'cuenta' => '631',
                'denominacion' => ' Otros tributos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            68 => 
            array (
                'id' => 570,
                'cuenta' => '633',
                'denominacion' => ' Ajustes negativos en la imposición sobre be neficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            69 => 
            array (
                'id' => 571,
                'cuenta' => '634',
                'denominacion' => ' Ajustes negativos en la imposición indirecta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            70 => 
            array (
                'id' => 572,
                'cuenta' => '6341',
                'denominacion' => ' Ajustes negativos en IVA de activo corriente',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            71 => 
            array (
                'id' => 573,
                'cuenta' => '6342',
                'denominacion' => ' Ajustes negativos en IVA de inversiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            72 => 
            array (
                'id' => 574,
                'cuenta' => '636',
                'denominacion' => ' Devolución de impuestos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            73 => 
            array (
                'id' => 575,
                'cuenta' => '638',
                'denominacion' => ' Ajustes positivos en la imposición sobre beneficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            74 => 
            array (
                'id' => 576,
                'cuenta' => '639',
                'denominacion' => ' Ajustes positivos en la imposición indirecta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            75 => 
            array (
                'id' => 577,
                'cuenta' => '6391',
                'denominacion' => ' Ajustes positivos en IVA de activo corriente',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            76 => 
            array (
                'id' => 578,
                'cuenta' => '6392',
                'denominacion' => ' Ajustes positivos en IVA de inversiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            77 => 
            array (
                'id' => 579,
                'cuenta' => '64',
                'denominacion' => ' GASTOS DE PERSONAL',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            78 => 
            array (
                'id' => 580,
                'cuenta' => '640',
                'denominacion' => ' Sueldos y salarios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            79 => 
            array (
                'id' => 581,
                'cuenta' => '641',
                'denominacion' => ' Indemnizaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            80 => 
            array (
                'id' => 582,
                'cuenta' => '642',
                'denominacion' => ' Seguridad Social a cargo de la empresa',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            81 => 
            array (
                'id' => 583,
                'cuenta' => '643',
                'denominacion' => ' Retribuciones a largo plazo mediante sistemas de aportación definida',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            82 => 
            array (
                'id' => 584,
                'cuenta' => '644',
                'denominacion' => ' Retribuciones a largo plazo mediante sistemas de prestación definida',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            83 => 
            array (
                'id' => 585,
                'cuenta' => '6440',
                'denominacion' => ' Contribuciones anuales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            84 => 
            array (
                'id' => 586,
                'cuenta' => '6442',
                'denominacion' => ' Otros costes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            85 => 
            array (
                'id' => 587,
                'cuenta' => '645',
                'denominacion' => ' Retribuciones al personal mediante instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            86 => 
            array (
                'id' => 588,
                'cuenta' => '6450',
                'denominacion' => ' Retribuciones al personal liquidados con instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            87 => 
            array (
                'id' => 589,
                'cuenta' => '6457',
                'denominacion' => ' Retribuciones al personal liquidados en efectivo basado en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            88 => 
            array (
                'id' => 590,
                'cuenta' => '649',
                'denominacion' => ' Otros gastos sociales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            89 => 
            array (
                'id' => 591,
                'cuenta' => '65',
                'denominacion' => ' OTROS GASTOS DE GESTIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            90 => 
            array (
                'id' => 592,
                'cuenta' => '650',
                'denominacion' => ' Pérdidas de créditos comerciales incobrables',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            91 => 
            array (
                'id' => 593,
                'cuenta' => '651',
                'denominacion' => ' Resultados de operaciones en común',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            92 => 
            array (
                'id' => 594,
                'cuenta' => '6510',
            'denominacion' => ' Beneficio transferido (gestor)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            93 => 
            array (
                'id' => 595,
                'cuenta' => '6511',
            'denominacion' => ' Pérdida soportada (partícipe o asociado no gestor)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            94 => 
            array (
                'id' => 596,
                'cuenta' => '659',
                'denominacion' => ' Otras pérdidas en gestión corriente',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            95 => 
            array (
                'id' => 597,
                'cuenta' => '66',
                'denominacion' => ' GASTOS FINANCIEROS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            96 => 
            array (
                'id' => 598,
                'cuenta' => '660',
                'denominacion' => ' Gastos financieros por actualización de provisiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            97 => 
            array (
                'id' => 599,
                'cuenta' => '661',
                'denominacion' => ' Intereses de obligaciones y bonos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            98 => 
            array (
                'id' => 600,
                'cuenta' => '6610',
                'denominacion' => ' Intereses de obligaciones y bonos a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            99 => 
            array (
                'id' => 601,
                'cuenta' => '6611',
                'denominacion' => ' Intereses de obligaciones y bonos a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            100 => 
            array (
                'id' => 602,
                'cuenta' => '6612',
                'denominacion' => ' Intereses de obligaciones y bonos a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            101 => 
            array (
                'id' => 603,
                'cuenta' => '6613',
                'denominacion' => ' Intereses de obligaciones y bonos a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            102 => 
            array (
                'id' => 604,
                'cuenta' => '6615',
                'denominacion' => ' Intereses de obligaciones y bonos a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            103 => 
            array (
                'id' => 605,
                'cuenta' => '6616',
                'denominacion' => ' Intereses de obligaciones y bonos a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            104 => 
            array (
                'id' => 606,
                'cuenta' => '6617',
                'denominacion' => ' Intereses de obligaciones y bonos a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            105 => 
            array (
                'id' => 607,
                'cuenta' => '6618',
                'denominacion' => ' Intereses de obligaciones y bonos a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            106 => 
            array (
                'id' => 608,
                'cuenta' => '662',
                'denominacion' => ' Intereses de deudas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            107 => 
            array (
                'id' => 609,
                'cuenta' => '6620',
                'denominacion' => ' Intereses de deudas, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            108 => 
            array (
                'id' => 610,
                'cuenta' => '6621',
                'denominacion' => ' Intereses de deudas, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            109 => 
            array (
                'id' => 611,
                'cuenta' => '6622',
                'denominacion' => ' Intereses de deudas, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            110 => 
            array (
                'id' => 612,
                'cuenta' => '6623',
                'denominacion' => ' Intereses de deudas con entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            111 => 
            array (
                'id' => 613,
                'cuenta' => '6624',
                'denominacion' => ' Intereses de deudas, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            112 => 
            array (
                'id' => 614,
                'cuenta' => '663',
                'denominacion' => ' Pérdidas por valoración de instrumentos financieros por su valor razonable',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            113 => 
            array (
                'id' => 615,
                'cuenta' => '6630',
                'denominacion' => ' Pérdidas de cartera de negociación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            114 => 
            array (
                'id' => 616,
                'cuenta' => '6631',
                'denominacion' => ' Pérdidas de designados por la empresa',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            115 => 
            array (
                'id' => 617,
                'cuenta' => '6632',
                'denominacion' => ' Pérdidas de disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            116 => 
            array (
                'id' => 618,
                'cuenta' => '6633',
                'denominacion' => ' Pérdidas de instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            117 => 
            array (
                'id' => 619,
                'cuenta' => '664',
                'denominacion' => ' Gastos por dividendos de acciones o participaciones consideradas como pasivos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            118 => 
            array (
                'id' => 620,
                'cuenta' => '6640',
                'denominacion' => ' Dividendos de pasivos, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            119 => 
            array (
                'id' => 621,
                'cuenta' => '6641',
                'denominacion' => ' Dividendos de pasivos, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            120 => 
            array (
                'id' => 622,
                'cuenta' => '6642',
                'denominacion' => ' Dividendos de pasivos, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            121 => 
            array (
                'id' => 623,
                'cuenta' => '6643',
                'denominacion' => ' Dividendos de pasivos, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            122 => 
            array (
                'id' => 624,
                'cuenta' => '665',
                'denominacion' => ' Intereses por descuento de efectos y operaciones de “factoring”',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            123 => 
            array (
                'id' => 625,
                'cuenta' => '6650',
                'denominacion' => ' Intereses por descuento de efectos en entidades de crédito del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            124 => 
            array (
                'id' => 626,
                'cuenta' => '6651',
                'denominacion' => ' Intereses por descuento de efectos en entidades de crédito asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            125 => 
            array (
                'id' => 627,
                'cuenta' => '6652',
                'denominacion' => ' Intereses por descuento de efectos en otras entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            126 => 
            array (
                'id' => 628,
                'cuenta' => '6653',
                'denominacion' => ' Intereses por descuento de efectos en otras entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            127 => 
            array (
                'id' => 629,
                'cuenta' => '6654',
                'denominacion' => ' Intereses por operaciones de “factoring” con entidades de crédito del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            128 => 
            array (
                'id' => 630,
                'cuenta' => '6655',
                'denominacion' => ' Intereses por operaciones de “factoring” con entidades de crédito asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            129 => 
            array (
                'id' => 631,
                'cuenta' => '6656',
                'denominacion' => ' Intereses por operaciones de “factoring” con otras entidades de crédito vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            130 => 
            array (
                'id' => 632,
                'cuenta' => '6657',
                'denominacion' => ' Intereses por operaciones de “factoring” con otras entidades de crédito',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            131 => 
            array (
                'id' => 633,
                'cuenta' => '666',
                'denominacion' => ' Pérdidas en participaciones y valores representativos de deuda',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            132 => 
            array (
                'id' => 634,
                'cuenta' => '6660',
                'denominacion' => ' Pérdidas en valores representativos de deuda a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            133 => 
            array (
                'id' => 635,
                'cuenta' => '6661',
                'denominacion' => ' Pérdidas en valores representativos de deuda a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            134 => 
            array (
                'id' => 636,
                'cuenta' => '6662',
                'denominacion' => ' Pérdidas en valores representativos de deuda a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            135 => 
            array (
                'id' => 637,
                'cuenta' => '6663',
                'denominacion' => ' Pérdidas en participaciones y valores representativos de deuda a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            136 => 
            array (
                'id' => 638,
                'cuenta' => '6665',
                'denominacion' => ' Pérdidas en participaciones y valores representativos de deuda a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            137 => 
            array (
                'id' => 639,
                'cuenta' => '6666',
                'denominacion' => ' Pérdidas en participaciones y valores representativos de deuda a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            138 => 
            array (
                'id' => 640,
                'cuenta' => '6667',
                'denominacion' => ' Pérdidas en valores representativos de deuda a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            139 => 
            array (
                'id' => 641,
                'cuenta' => '6668',
                'denominacion' => ' Pérdidas en valores representativos de deuda a corto plazo, otras empresas ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            140 => 
            array (
                'id' => 642,
                'cuenta' => '667',
                'denominacion' => ' Pérdidas de créditos no comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            141 => 
            array (
                'id' => 643,
                'cuenta' => '6670',
                'denominacion' => ' Pérdidas de créditos a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            142 => 
            array (
                'id' => 644,
                'cuenta' => '6671',
                'denominacion' => ' Pérdidas de créditos a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            143 => 
            array (
                'id' => 645,
                'cuenta' => '6672',
                'denominacion' => ' Pérdidas de créditos a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            144 => 
            array (
                'id' => 646,
                'cuenta' => '6673',
                'denominacion' => ' Pérdidas de créditos a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            145 => 
            array (
                'id' => 647,
                'cuenta' => '6675',
                'denominacion' => ' Pérdidas de créditos a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            146 => 
            array (
                'id' => 648,
                'cuenta' => '6676',
                'denominacion' => ' Pérdidas de créditos a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            147 => 
            array (
                'id' => 649,
                'cuenta' => '6677',
                'denominacion' => ' Pérdidas de créditos a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            148 => 
            array (
                'id' => 650,
                'cuenta' => '6678',
                'denominacion' => ' Pérdidas de créditos a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            149 => 
            array (
                'id' => 651,
                'cuenta' => '668',
                'denominacion' => ' Diferencias negativas de cambio ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            150 => 
            array (
                'id' => 652,
                'cuenta' => '669',
                'denominacion' => ' Otros gastos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            151 => 
            array (
                'id' => 653,
                'cuenta' => '67',
                'denominacion' => ' PÉRDIDAS PROCEDENTES DE ACTIVOS NO CORRIENTES Y GASTOS EXCEPCIONALES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            152 => 
            array (
                'id' => 654,
                'cuenta' => '670',
                'denominacion' => ' Pérdidas procedentes del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            153 => 
            array (
                'id' => 655,
                'cuenta' => '671',
                'denominacion' => ' Pérdidas procedentes del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            154 => 
            array (
                'id' => 656,
                'cuenta' => '672',
                'denominacion' => ' Pérdidas procedentes de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            155 => 
            array (
                'id' => 657,
                'cuenta' => '673',
                'denominacion' => ' Pérdidas procedentes de participaciones a largo plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            156 => 
            array (
                'id' => 658,
                'cuenta' => '6733',
                'denominacion' => ' Pérdidas procedentes de participaciones a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            157 => 
            array (
                'id' => 659,
                'cuenta' => '6734',
                'denominacion' => ' Pérdidas procedentes de participaciones a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            158 => 
            array (
                'id' => 660,
                'cuenta' => '6735',
                'denominacion' => ' Pérdidas procedentes de participaciones a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            159 => 
            array (
                'id' => 661,
                'cuenta' => '675',
                'denominacion' => ' Pérdidas por operaciones con obli gaciones propias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            160 => 
            array (
                'id' => 662,
                'cuenta' => '678',
                'denominacion' => ' Gastos excepcionales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            161 => 
            array (
                'id' => 663,
                'cuenta' => '68',
                'denominacion' => ' DOTACIONES PARA AMORTIZACIONES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            162 => 
            array (
                'id' => 664,
                'cuenta' => '680',
                'denominacion' => ' Amortización del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            163 => 
            array (
                'id' => 665,
                'cuenta' => '681',
                'denominacion' => ' Amortización del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            164 => 
            array (
                'id' => 666,
                'cuenta' => '682',
                'denominacion' => ' Amortización de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            165 => 
            array (
                'id' => 667,
                'cuenta' => '69',
                'denominacion' => ' PÉRDIDAS POR DETERIORO Y OTRAS DOTACIONES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            166 => 
            array (
                'id' => 668,
                'cuenta' => '690',
                'denominacion' => ' Pérdidas por deterioro del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            167 => 
            array (
                'id' => 669,
                'cuenta' => '691',
                'denominacion' => ' Pérdidas por deterioro del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            168 => 
            array (
                'id' => 670,
                'cuenta' => '692',
                'denominacion' => ' Pérdidas por deterioro de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            169 => 
            array (
                'id' => 671,
                'cuenta' => '693',
                'denominacion' => ' Pérdidas por deterioro de existencias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            170 => 
            array (
                'id' => 672,
                'cuenta' => '6930',
                'denominacion' => ' Pérdidas por deterioro de productos terminados y en curso de fabricación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            171 => 
            array (
                'id' => 673,
                'cuenta' => '6931',
                'denominacion' => ' Pérdidas por deterioro de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            172 => 
            array (
                'id' => 674,
                'cuenta' => '6932',
                'denominacion' => ' Pérdidas por deterioro de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            173 => 
            array (
                'id' => 675,
                'cuenta' => '6933',
                'denominacion' => ' Pérdidas por deterioro de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            174 => 
            array (
                'id' => 676,
                'cuenta' => '694',
                'denominacion' => ' Pérdidas por deterioro de créditos por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            175 => 
            array (
                'id' => 677,
                'cuenta' => '695',
                'denominacion' => ' Dotación a la provisión por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            176 => 
            array (
                'id' => 678,
                'cuenta' => '6954',
                'denominacion' => ' Dotación a la provisión por contratos onerosos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            177 => 
            array (
                'id' => 679,
                'cuenta' => '6959',
                'denominacion' => ' Dotación a la provisión para otras operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            178 => 
            array (
                'id' => 680,
                'cuenta' => '696',
                'denominacion' => ' Pérdidas por deterioro de participaciones y valores representativos de deuda a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            179 => 
            array (
                'id' => 681,
                'cuenta' => '6960',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            180 => 
            array (
                'id' => 682,
                'cuenta' => '6961',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            181 => 
            array (
                'id' => 683,
                'cuenta' => '6962',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            182 => 
            array (
                'id' => 684,
                'cuenta' => '6963',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            183 => 
            array (
                'id' => 685,
                'cuenta' => '6965',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            184 => 
            array (
                'id' => 686,
                'cuenta' => '6966',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            185 => 
            array (
                'id' => 687,
                'cuenta' => '6967',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            186 => 
            array (
                'id' => 688,
                'cuenta' => '6968',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a largo plazo, de otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            187 => 
            array (
                'id' => 689,
                'cuenta' => '697',
                'denominacion' => ' Pérdidas por deterioro de créditos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            188 => 
            array (
                'id' => 690,
                'cuenta' => '6970',
                'denominacion' => ' Pérdidas por deterioro de créditos a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            189 => 
            array (
                'id' => 691,
                'cuenta' => '6971',
                'denominacion' => ' Pérdidas por deterioro de créditos a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            190 => 
            array (
                'id' => 692,
                'cuenta' => '6972',
                'denominacion' => ' Pérdidas por deterioro de créditos a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            191 => 
            array (
                'id' => 693,
                'cuenta' => '6973',
                'denominacion' => ' Pérdidas por deterioro de créditos a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            192 => 
            array (
                'id' => 694,
                'cuenta' => '698',
                'denominacion' => ' Pérdidas por deterioro de participaciones y valores representativos de deuda a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            193 => 
            array (
                'id' => 695,
                'cuenta' => '6980',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            194 => 
            array (
                'id' => 696,
                'cuenta' => '6981',
                'denominacion' => ' Pérdidas por deterioro de participaciones en instrumentos de patrimonio neto a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            195 => 
            array (
                'id' => 697,
                'cuenta' => '6985',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a corto plazo, empresas del grupo ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            196 => 
            array (
                'id' => 698,
                'cuenta' => '6986',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            197 => 
            array (
                'id' => 699,
                'cuenta' => '6987',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            198 => 
            array (
                'id' => 700,
                'cuenta' => '6988',
                'denominacion' => ' Pérdidas por deterioro en valores representativos de deuda a corto plazo, de otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            199 => 
            array (
                'id' => 701,
                'cuenta' => '699',
                'denominacion' => ' Pérdidas por deterioro de créditos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            200 => 
            array (
                'id' => 702,
                'cuenta' => '6990',
                'denominacion' => ' Pérdidas por deterioro de créditos a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            201 => 
            array (
                'id' => 703,
                'cuenta' => '6991',
                'denominacion' => ' Pérdidas por deterioro de créditos a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            202 => 
            array (
                'id' => 704,
                'cuenta' => '6992',
                'denominacion' => ' Pérdidas por deterioro de créditos a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            203 => 
            array (
                'id' => 705,
                'cuenta' => '6993',
                'denominacion' => ' Pérdidas por deterioro de créditos a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            204 => 
            array (
                'id' => 706,
                'cuenta' => '7',
                'denominacion' => 'VENTAS E INGRESOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            205 => 
            array (
                'id' => 707,
                'cuenta' => '70',
                'denominacion' => ' VENTAS DE MERCADERÍAS, DE PRODUCCIÓN PROPIA, DE SERVICIOS, ETC',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            206 => 
            array (
                'id' => 708,
                'cuenta' => '700',
                'denominacion' => ' Ventas de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            207 => 
            array (
                'id' => 709,
                'cuenta' => '701',
                'denominacion' => ' Ventas de productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            208 => 
            array (
                'id' => 710,
                'cuenta' => '702',
                'denominacion' => ' Ventas de productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            209 => 
            array (
                'id' => 711,
                'cuenta' => '703',
                'denominacion' => ' Ventas de subproductos y residuos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            210 => 
            array (
                'id' => 712,
                'cuenta' => '704',
                'denominacion' => ' Ventas de envases y embalajes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            211 => 
            array (
                'id' => 713,
                'cuenta' => '705',
                'denominacion' => ' Prestaciones de servicios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            212 => 
            array (
                'id' => 714,
                'cuenta' => '706',
                'denominacion' => ' Descuentos sobre ventas por pronto pago',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            213 => 
            array (
                'id' => 715,
                'cuenta' => '7060',
                'denominacion' => ' Descuentos sobre ventas por pronto pago de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            214 => 
            array (
                'id' => 716,
                'cuenta' => '7061',
                'denominacion' => ' Descuentos sobre ventas por pronto pago de productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            215 => 
            array (
                'id' => 717,
                'cuenta' => '7062',
                'denominacion' => ' Descuentos sobre ventas por pronto pago de productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            216 => 
            array (
                'id' => 718,
                'cuenta' => '7063',
                'denominacion' => ' Descuentos sobre ventas por pronto pago de subproductos y residuos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            217 => 
            array (
                'id' => 719,
                'cuenta' => '708',
                'denominacion' => ' Devoluciones de ventas y operaciones similares',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            218 => 
            array (
                'id' => 720,
                'cuenta' => '7080',
                'denominacion' => ' Devoluciones de ventas de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            219 => 
            array (
                'id' => 721,
                'cuenta' => '7081',
                'denominacion' => ' Devoluciones de ventas de productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            220 => 
            array (
                'id' => 722,
                'cuenta' => '7082',
                'denominacion' => ' Devoluciones de ventas de productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            221 => 
            array (
                'id' => 723,
                'cuenta' => '7083',
                'denominacion' => ' Devoluciones de ventas de subproductos y residuos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            222 => 
            array (
                'id' => 724,
                'cuenta' => '7084',
                'denominacion' => ' Devoluciones de ventas de envases y embalajes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            223 => 
            array (
                'id' => 725,
                'cuenta' => '709',
                'denominacion' => ' “Rappels” sobre ventas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            224 => 
            array (
                'id' => 726,
                'cuenta' => '7090',
                'denominacion' => ' «Rappels» sobre ventas de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            225 => 
            array (
                'id' => 727,
                'cuenta' => '7091',
                'denominacion' => ' “Rappels” sobre ventas de productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            226 => 
            array (
                'id' => 728,
                'cuenta' => '7092',
                'denominacion' => ' “Rappels” sobre ventas de productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            227 => 
            array (
                'id' => 729,
                'cuenta' => '7093',
                'denominacion' => ' “Rappels” sobre ventas de subproductos y residuos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            228 => 
            array (
                'id' => 730,
                'cuenta' => '7094',
                'denominacion' => ' “Rappels” sobre ventas de envases y embalajes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            229 => 
            array (
                'id' => 731,
                'cuenta' => '71',
                'denominacion' => ' VARIACIÓN DE EXISTENCIAS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            230 => 
            array (
                'id' => 732,
                'cuenta' => '710',
                'denominacion' => ' Variación de existencias de productos en curso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            231 => 
            array (
                'id' => 733,
                'cuenta' => '711',
                'denominacion' => ' Variación de existencias de productos semiterminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            232 => 
            array (
                'id' => 734,
                'cuenta' => '712',
                'denominacion' => ' Variación de existencias de productos terminados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            233 => 
            array (
                'id' => 735,
                'cuenta' => '713',
                'denominacion' => ' Variación de existencias de subproductos, residuos y materiales recuperados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            234 => 
            array (
                'id' => 736,
                'cuenta' => '73',
                'denominacion' => ' TRABAJOS REALIZADOS PARA LA EMPRESA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            235 => 
            array (
                'id' => 737,
                'cuenta' => '730',
                'denominacion' => ' Trabajos realizados para el inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            236 => 
            array (
                'id' => 738,
                'cuenta' => '731',
                'denominacion' => ' Trabajos realizados para el inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            237 => 
            array (
                'id' => 739,
                'cuenta' => '732',
                'denominacion' => ' Trabajos realizados en inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            238 => 
            array (
                'id' => 740,
                'cuenta' => '733',
                'denominacion' => ' Trabajos realizados para el inmovilizado material en curso',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            239 => 
            array (
                'id' => 741,
                'cuenta' => '74',
                'denominacion' => ' SUBVENCIONES, DONACIONES Y LEGADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            240 => 
            array (
                'id' => 742,
                'cuenta' => '740',
                'denominacion' => ' Subvenciones, donaciones y legados a la explotación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            241 => 
            array (
                'id' => 743,
                'cuenta' => '746',
                'denominacion' => ' Subvenciones, donaciones y legados de capital transferidos al resultado del ejercicio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            242 => 
            array (
                'id' => 744,
                'cuenta' => '747',
                'denominacion' => ' Otras subvenciones, donaciones y legados transferidos al resultado del ejercicio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            243 => 
            array (
                'id' => 745,
                'cuenta' => '75',
                'denominacion' => ' OTROS INGRESOS DE GESTIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            244 => 
            array (
                'id' => 746,
                'cuenta' => '751',
                'denominacion' => ' Resultados de operaciones en común',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            245 => 
            array (
                'id' => 747,
                'cuenta' => '7510',
            'denominacion' => ' Pérdida transferida (gestor)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            246 => 
            array (
                'id' => 748,
                'cuenta' => '7511',
            'denominacion' => ' Beneficio atribuido (partícipe o asociado no gestor)',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            247 => 
            array (
                'id' => 749,
                'cuenta' => '752',
                'denominacion' => ' Ingresos por arrendamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            248 => 
            array (
                'id' => 750,
                'cuenta' => '753',
                'denominacion' => ' Ingresos de propiedad industrial cedida en explotación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            249 => 
            array (
                'id' => 751,
                'cuenta' => '754',
                'denominacion' => ' Ingresos por comisiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            250 => 
            array (
                'id' => 752,
                'cuenta' => '755',
                'denominacion' => ' Ingresos por servicios al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            251 => 
            array (
                'id' => 753,
                'cuenta' => '759',
                'denominacion' => ' Ingresos por servicios diversos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            252 => 
            array (
                'id' => 754,
                'cuenta' => '76',
                'denominacion' => ' INGRESOS FINANCIEROS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            253 => 
            array (
                'id' => 755,
                'cuenta' => '760',
                'denominacion' => ' Ingresos de participaciones en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            254 => 
            array (
                'id' => 756,
                'cuenta' => '7600',
                'denominacion' => ' Ingresos de participaciones en instrumentos de patrimonio, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            255 => 
            array (
                'id' => 757,
                'cuenta' => '7601',
                'denominacion' => ' Ingresos de participaciones en instrumentos de patrimonio, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            256 => 
            array (
                'id' => 758,
                'cuenta' => '7602',
                'denominacion' => ' Ingresos de participaciones en instrumentos de patrimonio, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            257 => 
            array (
                'id' => 759,
                'cuenta' => '7603',
                'denominacion' => ' Ingresos de participaciones en instrumentos de patrimonio, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            258 => 
            array (
                'id' => 760,
                'cuenta' => '761',
                'denominacion' => ' Ingresos de valores representativos de deuda',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            259 => 
            array (
                'id' => 761,
                'cuenta' => '7610',
                'denominacion' => ' Ingresos de valores representativos de deuda, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            260 => 
            array (
                'id' => 762,
                'cuenta' => '7611',
                'denominacion' => ' Ingresos de valores representativos de deuda, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            261 => 
            array (
                'id' => 763,
                'cuenta' => '7612',
                'denominacion' => ' Ingresos de valores representativos de deuda, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            262 => 
            array (
                'id' => 764,
                'cuenta' => '7613',
                'denominacion' => ' Ingresos de valores representativos de deuda, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            263 => 
            array (
                'id' => 765,
                'cuenta' => '762',
                'denominacion' => ' Ingresos de créditos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            264 => 
            array (
                'id' => 766,
                'cuenta' => '7620',
                'denominacion' => ' Ingresos de créditos a largo plazo ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            265 => 
            array (
                'id' => 767,
                'cuenta' => '76200',
                'denominacion' => ' Ingresos de créditos a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            266 => 
            array (
                'id' => 768,
                'cuenta' => '76201',
                'denominacion' => ' Ingresos de créditos a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            267 => 
            array (
                'id' => 769,
                'cuenta' => '76202',
                'denominacion' => ' Ingresos de créditos a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            268 => 
            array (
                'id' => 770,
                'cuenta' => '76203',
                'denominacion' => ' Ingresos de créditos a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            269 => 
            array (
                'id' => 771,
                'cuenta' => '7621',
                'denominacion' => ' Ingresos de créditos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            270 => 
            array (
                'id' => 772,
                'cuenta' => '76210',
                'denominacion' => ' Ingresos de créditos a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            271 => 
            array (
                'id' => 773,
                'cuenta' => '76211',
                'denominacion' => ' Ingresos de créditos a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            272 => 
            array (
                'id' => 774,
                'cuenta' => '76212',
                'denominacion' => ' Ingresos de créditos a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            273 => 
            array (
                'id' => 775,
                'cuenta' => '76213',
                'denominacion' => ' Ingresos de créditos a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            274 => 
            array (
                'id' => 776,
                'cuenta' => '763',
                'denominacion' => ' Beneficios por valoración de instrumentos financieros por su valor razonable',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            275 => 
            array (
                'id' => 777,
                'cuenta' => '7630',
                'denominacion' => ' Beneficios de cartera de negociación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            276 => 
            array (
                'id' => 778,
                'cuenta' => '7631',
                'denominacion' => ' Beneficios de designados por la empresa',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            277 => 
            array (
                'id' => 779,
                'cuenta' => '7632',
                'denominacion' => ' Beneficios de disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            278 => 
            array (
                'id' => 780,
                'cuenta' => '7633',
                'denominacion' => ' Beneficios de instrumentos de cobertura',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            279 => 
            array (
                'id' => 781,
                'cuenta' => '766',
                'denominacion' => ' Beneficios en participaciones y valores representativos de deuda',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            280 => 
            array (
                'id' => 782,
                'cuenta' => '7660',
                'denominacion' => ' Beneficios en valores representativos de deuda a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            281 => 
            array (
                'id' => 783,
                'cuenta' => '7661',
                'denominacion' => ' Beneficios en valores representativos de deuda a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            282 => 
            array (
                'id' => 784,
                'cuenta' => '7662',
                'denominacion' => ' Beneficios en valores representativos de deuda a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            283 => 
            array (
                'id' => 785,
                'cuenta' => '7663',
                'denominacion' => ' Beneficios en participaciones y valores representativos de deuda a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            284 => 
            array (
                'id' => 786,
                'cuenta' => '7665',
                'denominacion' => ' Beneficios en participaciones y valores representativos de deuda a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            285 => 
            array (
                'id' => 787,
                'cuenta' => '7666',
                'denominacion' => ' Beneficios en participaciones y valores representativos de deuda a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            286 => 
            array (
                'id' => 788,
                'cuenta' => '7667',
                'denominacion' => ' Beneficios en valores representativos de deuda a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            287 => 
            array (
                'id' => 789,
                'cuenta' => '7668',
                'denominacion' => ' Beneficios en valores representativos de deuda a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            288 => 
            array (
                'id' => 790,
                'cuenta' => '767',
                'denominacion' => ' Ingresos de activos afectos y de derechos de reembolso relativos a retribuciones a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            289 => 
            array (
                'id' => 791,
                'cuenta' => '768',
                'denominacion' => ' Diferencias positivas de cambio ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            290 => 
            array (
                'id' => 792,
                'cuenta' => '769',
                'denominacion' => ' Otros ingresos financieros',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            291 => 
            array (
                'id' => 793,
                'cuenta' => '77',
                'denominacion' => ' BENEFICIOS PROCEDENTES DE ACTIVOS NO CORRIENTES E INGRESOS EXCEPCIONALES',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            292 => 
            array (
                'id' => 794,
                'cuenta' => '770',
                'denominacion' => ' Beneficios procedentes del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            293 => 
            array (
                'id' => 795,
                'cuenta' => '771',
                'denominacion' => ' Beneficios procedentes del inmovilizado mate rial',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            294 => 
            array (
                'id' => 796,
                'cuenta' => '772',
                'denominacion' => ' Beneficios procedentes de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            295 => 
            array (
                'id' => 797,
                'cuenta' => '773',
                'denominacion' => ' Beneficios procedentes de participaciones a largo plazo en partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            296 => 
            array (
                'id' => 798,
                'cuenta' => '7733',
                'denominacion' => ' Beneficios procedentes de participaciones a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            297 => 
            array (
                'id' => 799,
                'cuenta' => '7734',
                'denominacion' => ' Beneficios procedentes de participaciones a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            298 => 
            array (
                'id' => 800,
                'cuenta' => '7735',
                'denominacion' => ' Beneficios procedentes de participaciones a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            299 => 
            array (
                'id' => 801,
                'cuenta' => '774',
                'denominacion' => ' Diferencia negativa en combinaciones de negocios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            300 => 
            array (
                'id' => 802,
                'cuenta' => '775',
                'denominacion' => ' Beneficios por operaciones con obligaciones propias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            301 => 
            array (
                'id' => 803,
                'cuenta' => '778',
                'denominacion' => ' Ingresos excepcionales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            302 => 
            array (
                'id' => 804,
                'cuenta' => '79',
                'denominacion' => ' EXCESOS Y APLICACIONES DE PROVISIONES Y DE PÉRDIDAS POR DETERIORO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            303 => 
            array (
                'id' => 805,
                'cuenta' => '790',
                'denominacion' => ' Reversión del deterioro del inmovilizado intangible',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            304 => 
            array (
                'id' => 806,
                'cuenta' => '791',
                'denominacion' => ' Reversión del deterioro del inmovilizado material',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            305 => 
            array (
                'id' => 807,
                'cuenta' => '792',
                'denominacion' => ' Reversión del deterioro de las inversiones inmobiliarias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            306 => 
            array (
                'id' => 808,
                'cuenta' => '793',
                'denominacion' => ' Reversión del deterioro de existencias',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            307 => 
            array (
                'id' => 809,
                'cuenta' => '7930',
                'denominacion' => ' Reversión del deterioro de productos terminados y en curso de fabricación',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            308 => 
            array (
                'id' => 810,
                'cuenta' => '7931',
                'denominacion' => ' Reversión del deterioro de mercaderías',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            309 => 
            array (
                'id' => 811,
                'cuenta' => '7932',
                'denominacion' => ' Reversión del deterioro de materias primas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            310 => 
            array (
                'id' => 812,
                'cuenta' => '7933',
                'denominacion' => ' Reversión del deterioro de otros aprovisionamientos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            311 => 
            array (
                'id' => 813,
                'cuenta' => '794',
                'denominacion' => ' Reversión del deterioro de créditos por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            312 => 
            array (
                'id' => 814,
                'cuenta' => '795',
                'denominacion' => ' Exceso de provisiones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            313 => 
            array (
                'id' => 815,
                'cuenta' => '7950',
                'denominacion' => ' Exceso de provisión por retribuciones al personal',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            314 => 
            array (
                'id' => 816,
                'cuenta' => '7951',
                'denominacion' => ' Exceso de provisión para impuestos ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            315 => 
            array (
                'id' => 817,
                'cuenta' => '7952',
                'denominacion' => ' Exceso de provisión para otras responsabilidades',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            316 => 
            array (
                'id' => 818,
                'cuenta' => '7954',
                'denominacion' => ' Exceso de provisión por operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            317 => 
            array (
                'id' => 819,
                'cuenta' => '79544',
                'denominacion' => ' Exceso de provisión por contratos onerosos',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            318 => 
            array (
                'id' => 820,
                'cuenta' => '79549',
                'denominacion' => ' Exceso de provisión para otras operaciones comerciales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            319 => 
            array (
                'id' => 821,
                'cuenta' => '7955',
                'denominacion' => ' Exceso de provisión para actuaciones medioambientales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            320 => 
            array (
                'id' => 822,
                'cuenta' => '7956',
                'denominacion' => ' Exceso de provisión para reestructuraciones ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            321 => 
            array (
                'id' => 823,
                'cuenta' => '7957',
                'denominacion' => ' Exceso de provisión por transacciones con pagos basados en instrumentos de patrimonio',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            322 => 
            array (
                'id' => 824,
                'cuenta' => '796',
                'denominacion' => ' Reversión del deterioro de participaciones y valores representativos de deuda a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            323 => 
            array (
                'id' => 825,
                'cuenta' => '7960',
                'denominacion' => ' Reversión del deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            324 => 
            array (
                'id' => 826,
                'cuenta' => '7961',
                'denominacion' => ' Reversión del deterioro de participaciones en instrumentos de patrimonio neto a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            325 => 
            array (
                'id' => 827,
                'cuenta' => '7965',
                'denominacion' => ' Reversión del deterioro de valores representativos de deuda a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            326 => 
            array (
                'id' => 828,
                'cuenta' => '7966',
                'denominacion' => ' Reversión del deterioro de valores representativos de deuda a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            327 => 
            array (
                'id' => 829,
                'cuenta' => '7967',
                'denominacion' => ' Reversión del deterioro de valores representativos de deuda a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            328 => 
            array (
                'id' => 830,
                'cuenta' => '7968',
                'denominacion' => ' Reversión del deterioro de valores representativos de deuda a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            329 => 
            array (
                'id' => 831,
                'cuenta' => '797',
                'denominacion' => ' Reversión del deterioro de créditos a largo plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            330 => 
            array (
                'id' => 832,
                'cuenta' => '7970',
                'denominacion' => ' Reversión del deterioro de créditos a largo plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            331 => 
            array (
                'id' => 833,
                'cuenta' => '7971',
                'denominacion' => ' Reversión del deterioro de créditos a largo plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            332 => 
            array (
                'id' => 834,
                'cuenta' => '7972',
                'denominacion' => ' Reversión del deterioro de créditos a largo plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            333 => 
            array (
                'id' => 835,
                'cuenta' => '7973',
                'denominacion' => ' Reversión del deterioro de créditos a largo plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            334 => 
            array (
                'id' => 836,
                'cuenta' => '798',
                'denominacion' => ' Reversión del deterioro de participaciones y valores representativos de deuda a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            335 => 
            array (
                'id' => 837,
                'cuenta' => '7980',
                'denominacion' => ' Reversión del deterioro de participaciones en instrumentos de patrimonio neto a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            336 => 
            array (
                'id' => 838,
                'cuenta' => '7981',
                'denominacion' => ' Reversión del deterioro de participaciones en instrumentos de patrimonio neto a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            337 => 
            array (
                'id' => 839,
                'cuenta' => '7985',
                'denominacion' => ' Reversión del deterioro en valores representativos de deuda a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            338 => 
            array (
                'id' => 840,
                'cuenta' => '7986',
                'denominacion' => ' Reversión del deterioro en valores representativos de deuda a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            339 => 
            array (
                'id' => 841,
                'cuenta' => '7987',
                'denominacion' => ' Reversión del deterioro en valores representativos de deuda a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            340 => 
            array (
                'id' => 842,
                'cuenta' => '7988',
                'denominacion' => ' Reversión del deterioro en valores representativos de deuda a corto plazo, otras empresas ',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            341 => 
            array (
                'id' => 843,
                'cuenta' => '799',
                'denominacion' => ' Reversión del deterioro de créditos a corto plazo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            342 => 
            array (
                'id' => 844,
                'cuenta' => '7990',
                'denominacion' => ' Reversión del deterioro de créditos a corto plazo, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            343 => 
            array (
                'id' => 845,
                'cuenta' => '7991',
                'denominacion' => ' Reversión del deterioro de créditos a corto plazo, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            344 => 
            array (
                'id' => 846,
                'cuenta' => '7992',
                'denominacion' => ' Reversión del deterioro de créditos a corto plazo, otras partes vinculadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            345 => 
            array (
                'id' => 847,
                'cuenta' => '7993',
                'denominacion' => ' Reversión del deterioro de créditos a corto plazo, otras empresas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            346 => 
            array (
                'id' => 848,
                'cuenta' => '8',
                'denominacion' => 'GASTOS IMPUTADOS AL PATRIMONIO NETO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            347 => 
            array (
                'id' => 849,
                'cuenta' => '80',
                'denominacion' => ' GASTOS FINANCIEROS POR VALORACIÓN DE ACTIVOS Y PASIVOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            348 => 
            array (
                'id' => 850,
                'cuenta' => '800',
                'denominacion' => ' Pérdidas en activos financieros disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            349 => 
            array (
                'id' => 851,
                'cuenta' => '802',
                'denominacion' => ' Transferencia de beneficios en activos financieros disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            350 => 
            array (
                'id' => 852,
                'cuenta' => '81',
                'denominacion' => ' GASTOS EN OPERACIONES DE COBERTURA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            351 => 
            array (
                'id' => 853,
                'cuenta' => '810',
                'denominacion' => ' Pérdidas por coberturas de flujos de efectivo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            352 => 
            array (
                'id' => 854,
                'cuenta' => '811',
                'denominacion' => ' Pérdidas por coberturas de inversiones netas en un negocio en el extranjero',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            353 => 
            array (
                'id' => 855,
                'cuenta' => '812',
                'denominacion' => ' Transferencia de beneficios por coberturas de flujos de efectivo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            354 => 
            array (
                'id' => 856,
                'cuenta' => '813',
                'denominacion' => ' Transferencia de beneficios por coberturas de inversiones netas en un negocio en el extranjero',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            355 => 
            array (
                'id' => 857,
                'cuenta' => '82',
                'denominacion' => ' GASTOS POR DIFERENCIAS DE CONVERSIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            356 => 
            array (
                'id' => 858,
                'cuenta' => '820',
                'denominacion' => ' Diferencias de conversión negativas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            357 => 
            array (
                'id' => 859,
                'cuenta' => '821',
                'denominacion' => ' Transferencia de diferencias de conversión positivas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            358 => 
            array (
                'id' => 860,
                'cuenta' => '83',
                'denominacion' => ' IMPUESTO SOBRE BENEFICIOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            359 => 
            array (
                'id' => 861,
                'cuenta' => '830',
                'denominacion' => ' Impuesto sobre beneficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            360 => 
            array (
                'id' => 862,
                'cuenta' => '8300',
                'denominacion' => ' Impuesto corriente',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            361 => 
            array (
                'id' => 863,
                'cuenta' => '8301',
                'denominacion' => ' Impuesto diferido',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            362 => 
            array (
                'id' => 864,
                'cuenta' => '833',
                'denominacion' => ' Ajustes negativos en la imposición sobre beneficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            363 => 
            array (
                'id' => 865,
                'cuenta' => '834',
                'denominacion' => ' Ingresos fiscales por diferencias permanentes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            364 => 
            array (
                'id' => 866,
                'cuenta' => '835',
                'denominacion' => ' Ingresos fiscales por deducciones y bonificaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            365 => 
            array (
                'id' => 867,
                'cuenta' => '836',
                'denominacion' => ' Transferencia de diferencias permanentes',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            366 => 
            array (
                'id' => 868,
                'cuenta' => '837',
                'denominacion' => ' Transferencia de deducciones y bonificaciones',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            367 => 
            array (
                'id' => 869,
                'cuenta' => '838',
                'denominacion' => ' Ajustes positivos en la imposición sobre beneficios',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            368 => 
            array (
                'id' => 870,
                'cuenta' => '84',
                'denominacion' => ' TRANSFERENCIAS DE SUBVENCIONES, DONACIONES Y LEGADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            369 => 
            array (
                'id' => 871,
                'cuenta' => '840',
                'denominacion' => ' Transferencia de subvenciones oficiales de capital',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            370 => 
            array (
                'id' => 872,
                'cuenta' => '841',
                'denominacion' => ' Transferencia de donaciones y legados de capital',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            371 => 
            array (
                'id' => 873,
                'cuenta' => '842',
                'denominacion' => ' Transferencia de otras subvenciones, donaciones y legados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            372 => 
            array (
                'id' => 874,
                'cuenta' => '85',
                'denominacion' => ' GASTOS POR PÉRDIDAS ACTUARIALES Y AJUSTES EN LOS ACTIVOS POR RETRIBUCIONES A LARGO PLAZO DE PRESTACIÓN DEFINIDA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            373 => 
            array (
                'id' => 875,
                'cuenta' => '850',
                'denominacion' => ' Pérdidas actuariales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            374 => 
            array (
                'id' => 876,
                'cuenta' => '851',
                'denominacion' => ' Ajustes negativos en activos por retribuciones a largo plazo de prestación definida',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            375 => 
            array (
                'id' => 877,
                'cuenta' => '86',
                'denominacion' => ' GASTOS POR ACTIVOS NO CORRIENTES EN VENTA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            376 => 
            array (
                'id' => 878,
                'cuenta' => '860',
                'denominacion' => ' Pérdidas en activos no corrientes y grupos enajenables de elementos mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            377 => 
            array (
                'id' => 879,
                'cuenta' => '862',
                'denominacion' => ' Transferencia de beneficios en activos no corrientes y grupos enajenables de elementos mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            378 => 
            array (
                'id' => 880,
                'cuenta' => '89',
                'denominacion' => ' GASTOS DE PARTICIPACIONES EN EMPRESAS DEL GRUPO O ASOCIADAS CON AJUSTES VALORATIVOS POSITIVOS PREVIOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            379 => 
            array (
                'id' => 881,
                'cuenta' => '891',
                'denominacion' => ' Deterioro de participaciones en el patrimonio, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            380 => 
            array (
                'id' => 882,
                'cuenta' => '892',
                'denominacion' => ' Deterioro de participaciones en el patrimonio, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            381 => 
            array (
                'id' => 883,
                'cuenta' => '9',
                'denominacion' => 'INGRESOS IMPUTADOS AL PATRIMONIO NETO',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            382 => 
            array (
                'id' => 884,
                'cuenta' => '90',
                'denominacion' => ' INGRESOS FINANCIEROS POR VALORACIÓN DE ACTIVOS Y PASIVOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            383 => 
            array (
                'id' => 885,
                'cuenta' => '900',
                'denominacion' => ' Beneficios en activos financieros disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            384 => 
            array (
                'id' => 886,
                'cuenta' => '902',
                'denominacion' => ' Transferencia de pérdidas de activos financieros disponibles para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            385 => 
            array (
                'id' => 887,
                'cuenta' => '91',
                'denominacion' => ' INGRESOS EN OPERACIONES DE COBERTURA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            386 => 
            array (
                'id' => 888,
                'cuenta' => '910',
                'denominacion' => ' Beneficios por coberturas de flujos de efectivo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            387 => 
            array (
                'id' => 889,
                'cuenta' => '911',
                'denominacion' => ' Beneficios por coberturas de una inversión neta en un negocio en el extranjero',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            388 => 
            array (
                'id' => 890,
                'cuenta' => '912',
                'denominacion' => ' Transferencia de pérdidas por coberturas de flujos de efectivo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            389 => 
            array (
                'id' => 891,
                'cuenta' => '913',
                'denominacion' => ' Transferencia de pérdidas por coberturas de una inversión neta en un negocio en el extranjero',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            390 => 
            array (
                'id' => 892,
                'cuenta' => '92',
                'denominacion' => ' INGRESOS POR DIFERENCIAS DE CONVERSIÓN',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            391 => 
            array (
                'id' => 893,
                'cuenta' => '920',
                'denominacion' => ' Diferencias de conversión positivas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            392 => 
            array (
                'id' => 894,
                'cuenta' => '921',
                'denominacion' => ' Transferencia de diferencias de conversión negativas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            393 => 
            array (
                'id' => 895,
                'cuenta' => '94',
                'denominacion' => ' INGRESOS POR SUBVENCIONES, DONACIONES Y LEGADOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            394 => 
            array (
                'id' => 896,
                'cuenta' => '940',
                'denominacion' => ' Ingresos de subvenciones oficiales de capital',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            395 => 
            array (
                'id' => 897,
                'cuenta' => '941',
                'denominacion' => ' Ingresos de donaciones y legados de capital',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            396 => 
            array (
                'id' => 898,
                'cuenta' => '942',
                'denominacion' => ' Ingresos de otras subvenciones, donaciones y legados',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            397 => 
            array (
                'id' => 899,
                'cuenta' => '95',
                'denominacion' => ' INGRESOS POR GANANCIAS ACTUARIALES Y AJUSTES EN LOS ACTIVOS POR RETRIBUCIONES A LARGO PLAZO DE PRESTACIÓN DEFINIDA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            398 => 
            array (
                'id' => 900,
                'cuenta' => '950',
                'denominacion' => ' Ganancias actuariales',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            399 => 
            array (
                'id' => 901,
                'cuenta' => '951',
                'denominacion' => ' Ajustes positivos en activos por retribuciones a largo plazo de prestación definida',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            400 => 
            array (
                'id' => 902,
                'cuenta' => '96',
                'denominacion' => ' INGRESOS POR ACTIVOS NO CORRIENTES EN VENTA',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            401 => 
            array (
                'id' => 903,
                'cuenta' => '960',
                'denominacion' => ' Beneficios en activos no corrientes y grupos enajenables de elementos mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            402 => 
            array (
                'id' => 904,
                'cuenta' => '962',
                'denominacion' => ' Transferencia de pérdidas en activos no corrientes y grupos enajenables de elementos mantenidos para la venta',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            403 => 
            array (
                'id' => 905,
                'cuenta' => '99',
                'denominacion' => ' INGRESOS DE PARTICIPACIONES EN EMPRESAS DEL GRUPO O ASOCIADAS CON AJUSTES VALORATIVOS NEGATIVOS PREVIOS',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            404 => 
            array (
                'id' => 906,
                'cuenta' => '991',
                'denominacion' => ' Recuperación de ajustes valorativos negativos previos, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            405 => 
            array (
                'id' => 907,
                'cuenta' => '992',
                'denominacion' => ' Recuperación de ajustes valorativos negativos previos, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            406 => 
            array (
                'id' => 908,
                'cuenta' => '993',
                'denominacion' => ' Transferencia por deterioro de ajustes valorativos negativos previos, empresas del grupo',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
            407 => 
            array (
                'id' => 909,
                'cuenta' => '994',
                'denominacion' => ' Transferencia por deterioro de ajustes valorativos negativos previos, empresas asociadas',
                'created_at' => '2024-04-15 15:02:59',
                'updated_at' => '2024-04-15 15:02:59',
            ),
        ));
        
        
    }
}