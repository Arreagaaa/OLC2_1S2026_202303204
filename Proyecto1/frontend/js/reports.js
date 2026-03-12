// maneja la visualizacion y descarga de reportes

const reportView = document.getElementById('report-view');

// muestra la tabla de simbolos en el panel
document.getElementById('btn-report-symbols').addEventListener('click', () => {
    if (!window._lastResponse) {
        reportView.innerHTML = '<span class="report-placeholder">Ejecuta el codigo primero.</span>';
        return;
    }
    reportView.innerHTML = window._lastResponse.symbolsHtml || '<span class="report-placeholder">Sin simbolos.</span>';
});

// muestra la tabla de errores en el panel
document.getElementById('btn-report-errors').addEventListener('click', () => {
    if (!window._lastResponse) {
        reportView.innerHTML = '<span class="report-placeholder">Ejecuta el codigo primero.</span>';
        return;
    }
    reportView.innerHTML = window._lastResponse.errorsHtml || '<span class="report-placeholder">Sin errores.</span>';
});

// descarga el resultado de consola como archivo de texto
document.getElementById('btn-download-output').addEventListener('click', () => {
    if (!window._lastResponse) {
        alert('Ejecuta el codigo primero.');
        return;
    }
    const texto = window._lastResponse.output ?? '';
    const blob  = new Blob([texto], { type: 'text/plain' });
    const url   = URL.createObjectURL(blob);
    const a     = document.createElement('a');
    a.href      = url;
    a.download  = 'resultado.txt';
    a.click();
    URL.revokeObjectURL(url);
});

// descarga la tabla de simbolos como HTML
document.getElementById('btn-download-symbols').addEventListener('click', () => {
    if (!window._lastResponse) {
        alert('Ejecuta el codigo primero.');
        return;
    }
    const html = window._lastResponse.symbolsHtml ?? '<p>Sin simbolos.</p>';
    const blob = new Blob([html], { type: 'text/html' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'tabla-simbolos.html';
    a.click();
    URL.revokeObjectURL(url);
});

// descarga la tabla de errores como HTML
document.getElementById('btn-download-errors').addEventListener('click', () => {
    if (!window._lastResponse) {
        alert('Ejecuta el codigo primero.');
        return;
    }
    const html = window._lastResponse.errorsHtml ?? '<p>Sin errores.</p>';
    const blob = new Blob([html], { type: 'text/html' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'tabla-errores.html';
    a.click();
    URL.revokeObjectURL(url);
});

// cuando llega una respuesta nueva se limpia el reporte anterior
window.addEventListener('golampi:response', () => {
    reportView.innerHTML = '<span class="report-placeholder">Usa los botones para ver los reportes.</span>';
});
