// maneja el textarea del editor: numeros de linea, carga y guardado de archivos

const editor      = document.getElementById('editor');
const lineNumbers = document.getElementById('lineNumbers');
const fileInput   = document.getElementById('file-input');

// actualiza numeros de linea sincronizados con el textarea
function updateLineNumbers() {
    const lines = editor.value.split('\n').length;
    let nums = '';
    for (let i = 1; i <= lines; i++) {
        nums += i + '\n';
    }
    lineNumbers.textContent = nums;
}

// sincroniza scroll entre numeros y textarea
editor.addEventListener('scroll', () => {
    lineNumbers.scrollTop = editor.scrollTop;
});

editor.addEventListener('input', updateLineNumbers);

// tecla Tab inserta 4 espacios en vez de cambiar foco
editor.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
        e.preventDefault();
        const start = editor.selectionStart;
        const end   = editor.selectionEnd;
        editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(end);
        editor.selectionStart = editor.selectionEnd = start + 4;
        updateLineNumbers();
    }
});

// boton abrir archivo .gol
document.getElementById('btn-open').addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        editor.value = ev.target.result;
        updateLineNumbers();
    };
    reader.readAsText(file);
    // resetear para permitir volver a abrir el mismo archivo
    fileInput.value = '';
});

// boton guardar: descarga el contenido del editor como .gol
document.getElementById('btn-save').addEventListener('click', () => {
    const blob = new Blob([editor.value], { type: 'text/plain' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'programa.gol';
    a.click();
    URL.revokeObjectURL(url);
});

// boton limpiar editor
document.getElementById('btn-clear-editor').addEventListener('click', () => {
    editor.value = '';
    updateLineNumbers();
});

// boton limpiar consola
document.getElementById('btn-clear-console').addEventListener('click', () => {
    document.getElementById('console').textContent = '';
});

// inicializar numeros de linea
updateLineNumbers();
