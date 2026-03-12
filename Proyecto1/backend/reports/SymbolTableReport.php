<?php

namespace App\Reports;

use App\Models\Symbol;

// genera la tabla de simbolos como HTML
class SymbolTableReport
{
    // recibe array de Symbol, retorna string HTML
    public static function toHtml(array $symbols): string
    {
        $rows = '';
        foreach ($symbols as $sym) {
            if (is_bool($sym->valor)) {
                $value = $sym->valor ? 'true' : 'false';
            } elseif (is_scalar($sym->valor)) {
                $value = htmlspecialchars((string) $sym->valor);
            } elseif (is_array($sym->valor)) {
                $value = htmlspecialchars(json_encode($sym->valor, JSON_PRESERVE_ZERO_FRACTION));
            } else {
                $value = $sym->tipo;
            }
            $rows .= sprintf(
                '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%d</td><td>%d</td></tr>',
                htmlspecialchars($sym->id),
                htmlspecialchars($sym->tipo),
                htmlspecialchars($sym->clase),
                htmlspecialchars($sym->ambito),
                $value,
                $sym->fila,
                $sym->columna
            );
        }

        return <<<HTML
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Identificador</th><th>Tipo</th><th>Clase</th>
                    <th>Ambito</th><th>Valor</th><th>Fila</th><th>Columna</th>
                </tr>
            </thead>
            <tbody>$rows</tbody>
        </table>
        HTML;
    }
}
