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

// cuando llega una respuesta nueva se limpia el reporte anterior
window.addEventListener('golampi:response', () => {
    reportView.innerHTML = '<span class="report-placeholder">Usa los botones para ver los reportes.</span>';
});

// descarga el contenido del panel de reporte como HTML
function downloadReport(filename) {
    const html = reportView.innerHTML;
    const blob = new Blob([html], { type: 'text/html' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
}

// expone la funcion para uso desde consola del navegador si se necesita
window.downloadReport = downloadReport;
