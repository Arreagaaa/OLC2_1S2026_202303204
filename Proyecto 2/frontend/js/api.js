// comunicacion con el backend: envia codigo y distribuye la respuesta

// la URL del endpoint es relativa para no quemar rutas absolutas
const API_URL = '../backend/api/execute.php';
const QEMU_VALIDATE_URL = '../backend/api/validate_qemu.php';

// guarda la ultima respuesta para usarla en reports.js
window._lastResponse = null;

document.getElementById('btn-run').addEventListener('click', async () => {
    const codigo  = document.getElementById('editor').value;
    const consola = document.getElementById('console');

    consola.textContent = 'Compilando...';

    try {
        const res = await fetch(API_URL, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify({ codigo }),
        });

        if (!res.ok) {
            consola.textContent = `Error HTTP ${res.status}`;
            return;
        }

        const data = await res.json();
        window._lastResponse = data;

        // mostrar codigo ARM64 o errores en la consola
        if (data.errors && data.errors.length > 0) {
            const errLines = data.errors.map(
                e => `[${e.tipo}] Linea ${e.fila}, Col ${e.columna}: ${e.descripcion}`
            );
            consola.textContent = errLines.join('\n');
        } else {
            consola.textContent = data.arm64_code ?? '';
        }

        // notificar a reports.js que hay datos frescos
        window.dispatchEvent(new CustomEvent('golampi:response', { detail: data }));

    } catch (err) {
        consola.textContent = 'Error de red: ' + err.message;
    }
});

document.getElementById('btn-validate-qemu').addEventListener('click', async () => {
    const consola = document.getElementById('console');
    const reportView = document.getElementById('report-view');

    consola.textContent = 'Validando entradas oficiales en QEMU...';
    reportView.innerHTML = '<span class="report-placeholder">Ejecutando validacion QEMU...</span>';

    try {
        const res = await fetch(QEMU_VALIDATE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({}),
        });

        if (!res.ok) {
            consola.textContent = `Error HTTP ${res.status} al validar en QEMU`;
            return;
        }

        const data = await res.json();
        const summary = data.summary ?? { total: 0, passed: 0, failed: 0 };
        const results = data.results ?? [];

        const lines = [
            '=== VALIDACION QEMU OFICIAL ===',
            `Total: ${summary.total} | PASS: ${summary.passed} | FAIL: ${summary.failed}`,
            '',
        ];

        for (const item of results) {
            const notes = item.notes && item.notes.length > 0 ? item.notes.join(' | ') : 'OK';
            lines.push(`${item.file}: ${item.status} | fallback=${item.fallback ? 'YES' : 'NO'} | notes=${notes}`);
        }

        consola.textContent = lines.join('\n');

        const rows = results.map(item => {
            const notes = item.notes && item.notes.length > 0 ? item.notes.join(' | ') : 'OK';
            const statusColor = item.status === 'PASS' ? '#86efac' : '#fca5a5';
            return `
                <tr>
                    <td>${item.file}</td>
                    <td style="color:${statusColor};font-weight:bold">${item.status}</td>
                    <td>${item.assemble_ok ? 'YES' : 'NO'}</td>
                    <td>${item.link_ok ? 'YES' : 'NO'}</td>
                    <td>${item.run_ok ? 'YES' : 'NO'}</td>
                    <td>${item.fallback ? 'YES' : 'NO'}</td>
                    <td>${notes}</td>
                </tr>
            `;
        }).join('');

        reportView.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Assemble</th>
                        <th>Link</th>
                        <th>Run</th>
                        <th>Fallback</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `;
    } catch (err) {
        consola.textContent = 'Error de red al validar en QEMU: ' + err.message;
    }
});
