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

        // mostrar salida ejecutada o codigo ARM64 en la consola
        if (data.errors && data.errors.length > 0) {
            const errLines = data.errors.map(
                e => `[${e.tipo}] Linea ${e.fila}, Col ${e.columna}: ${e.descripcion}`
            );
            consola.textContent = errLines.join('\n');
        } else {
            // Mostrar salida ejecutada si está disponible, sino ARM64
            const displayOutput = data.executed_output || data.arm64_code || '';
            consola.textContent = displayOutput;
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

    consola.textContent = 'Validando entradas oficiales en ARM64...';
    reportView.innerHTML = '<span class="report-placeholder">Ejecutando validacion ARM64 general...</span>';

    try {
        const res = await fetch(QEMU_VALIDATE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({}),
        });

        if (!res.ok) {
            consola.textContent = `Error HTTP ${res.status} al validar ARM64`;
            return;
        }

        const data = await res.json();
        const summary = data.summary ?? { total: 0, passed: 0, failed: 0 };
        const results = data.results ?? [];

        const lines = [
            '=== VALIDACION ARM64 OFICIAL ===',
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
        consola.textContent = 'Error de red al validar ARM64: ' + err.message;
    }
});

const QEMU_SINGLE_URL = '../backend/api/validate_qemu_single.php';
const officialFiles = ['archivo1_basico.go', 'archivo2_intermedio.go', 'archivo3_funciones.go', 'archivo4_arreglos1d.go', 'archivo5_arreglos_ndim.go'];
let currentSingleIndex = 0;

document.getElementById('btn-validate-single').addEventListener('click', async () => {
    const consola = document.getElementById('console');
    const reportView = document.getElementById('report-view');

    currentSingleIndex = 0;
    consola.textContent = 'Validando archivo ARM64 1/5...';
    reportView.innerHTML = '<span class="report-placeholder">Validando archivo por archivo en ARM64...</span>';

    const validateNext = async () => {
        if (currentSingleIndex >= officialFiles.length) {
            consola.textContent += '\n\n=== VALIDACION COMPLETADA ===\nTodos los archivos fueron validados en ARM64.';
            return;
        }

        const fileName = officialFiles[currentSingleIndex];
        currentSingleIndex++;

        try {
            const res = await fetch(QEMU_SINGLE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ file: fileName }),
            });

            if (!res.ok) {
                consola.textContent += `\n\n[ERROR] ${fileName}: HTTP ${res.status}`;
                validateNext();
                return;
            }

            const data = await res.json();

            let output = `\n\n=== ${fileName} ===\n`;
            output += `Status: ${data.status}\n`;
            output += `Symbols: ${data.symbols_count}\n`;
            output += `Fallback: ${data.fallback ? 'YES' : 'NO'}\n`;
            output += `\nPasos:\n`;

            for (const step of data.steps || []) {
                const ok = step.ok ? '✓' : '✗';
                output += `  ${ok} ${step.step}`;
                if (step.symbols !== undefined) output += ` (${step.symbols} símbolos)`;
                if (step.fallback !== undefined) output += ` (fallback=${step.fallback ? 'YES' : 'NO'})`;
                if (step.error) output += ` - ERROR: ${step.error}`;
                if (step.match) output += ` - ${step.match}`;
                output += '\n';
            }

            if (data.status === 'PASS' && data.qemu_output) {
                output += `\nSalida ejecutada:\n${data.qemu_output}\n`;
            }

            consola.textContent += output;
            reportView.innerHTML = `<pre style="color:#c7f9cc;font-size:12px;overflow:auto;height:100%;padding:10px">${data.qemu_output || 'Sin salida'}</pre>`;

            // Validar siguiente archivo después de 1.5 segundos
            setTimeout(validateNext, 1500);
        } catch (err) {
            consola.textContent += `\n\n[ERROR] ${fileName}: ${err.message}`;
            validateNext();
        }
    };

    await validateNext();
});
