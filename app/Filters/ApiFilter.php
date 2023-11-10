<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    // Parametros de filtro
    protected $safeParams = [];
    // Mapeo de columnas de filtro
    protected $columnMap = [];
    // Mapeo de operadores
    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            // 1. Valor del parámetro
            $query = $request->query($param);

            // Si no hay valor de param, no hace nada
            if (!isset($query))
                continue;

            // ???
            $column = $this->columnMap[$param] ?? $param;

            // De la consulta de cada param, se buscar qué
            // operador va a utilizar
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [
                        $column,
                        $this->operatorMap[$operator],
                        $query[$operator]
                    ];
                }
            }

            return $eloQuery;
        }
    }
}
