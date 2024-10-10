<?php

namespace Database\Seeders;

use App\Models\RequestType as ModelsRequestType;
use Illuminate\Database\Seeder;

class RequestType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $RequestsTypes = array(
            (object)[
                'type' => 'Vacaciones',
                'description' => 'El usuario tiene derecho a tomar vaciones despúes de cumplir los 6 meses en la empresa.',
                'max_hours_peer_day' => null,
                'uses_peer_mont' => null,
                'continuos_days' => null,
                'max_continuos_uses' => null,
                'min_month_time' => 6,
                'comprobation' => null,
                'status' => 1
            ],
            (object)[
                'type' => 'Ausencia',
                'description' => 'El usuario tiene derecho a tener retardos, salir durante la jornada o salir antes de la oficina.',
                'max_hours_peer_day' => null,
                'uses_peer_mont' => null,
                'continuos_days' => null,
                'max_continuos_uses' => null,
                'min_month_time' => null,
                'comprobation' => null,
                'status' => 1
            ],
            (object)[
                'type' => 'Paternidad',
                'description' => 'El usuario tiene derecho a disfrutar 5 días cuando tenga un hijo, los días pueden ser menos de 5, pero no puede tomar los días por separado, es decir crear diferentes solicitudes.',
                'max_hours_peer_day' => null,
                'uses_peer_mont' => null,
                'continuos_days' => null,
                'max_continuos_uses' => null,
                'min_month_time' => null,
                'comprobation' => null,
                'status' => 1
            ],
            (object)[
                'type' => 'Incapacidad',
                'description' => 'El usuario puede tomar sus días de incapacidad, recordando que estos deben ser justificados y que los paga el IMSS.',
                'max_hours_peer_day' => null,
                'uses_peer_mont' => null,
                'continuos_days' => null,
                'max_continuos_uses' => null,
                'min_month_time' => null,
                'comprobation' => null,
                'status' => 1
            ],
            (object)[
                'type' => 'Permisos especiales',
                'description' => 'El usuario puede solicitar un permiso de asuntos personales despúes de los tres meses, los otros tipos los puede tomar cuando el los crea necesario.',
                'max_hours_peer_day' => null,
                'uses_peer_mont' => null,
                'continuos_days' => null,
                'max_continuos_uses' => null,
                'min_month_time' => null,
                'comprobation' => null,
                'status' => 1
            ],
        );

        foreach ($RequestsTypes as $requestType){
            ModelsRequestType::create([
                'type' => $requestType->type,
                'description' => $requestType->description,
                'max_hours_peer_day' => $requestType->max_hours_peer_day,
                'uses_peer_mont' => $requestType->uses_peer_mont,
                'continuos_days' => $requestType->continuos_days,
                'max_continuos_uses' => $requestType->max_continuos_uses,
                'min_month_time' => $requestType->min_month_time,
                'comprobation' => $requestType->comprobation,
                'status' => $requestType->status,
            ]);
        }
    }
}
