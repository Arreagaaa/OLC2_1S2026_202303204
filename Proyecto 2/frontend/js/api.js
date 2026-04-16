// comunicacion con el backend: envia codigo y distribuye la respuesta

// la URL del endpoint es relativa para no quemar rutas absolutas
const API_URL = '../backend/api/execute.php';

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
