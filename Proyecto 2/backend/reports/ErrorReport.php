<?php

namespace App\Reports;

use App\Models\ErrorEntry;

// genera el reporte de errores como HTML
class ErrorReport
{
    // recibe array de ErrorEntry, retorna string HTML
    public static function toHtml(array $errors): string
    {
        $rows = '';
        foreach ($errors as $err) {
            $rows .= sprintf(
                '<tr><td>%s</td><td>%s</td><td>%d</td><td>%d</td></tr>',
                htmlspecialchars($err->tipo),
                htmlspecialchars($err->descripcion),
                $err->fila,
                $err->columna
            );
        }

        return <<<HTML
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Tipo</th><th>Descripcion</th><th>Fila</th><th>Columna</th>
                </tr>
            </thead>
            <tbody>$rows</tbody>
        </table>
        HTML;
    }
}
