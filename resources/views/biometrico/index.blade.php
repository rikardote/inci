@extends('layout.main')

@section('title', 'Descarga de checadas de los biometricos')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button id="startProcess" class="btn btn-primary">
                        <i class="fa fa-plug"></i> Iniciar Proceso
                    </button>
                </div>

                <div class="card-body">
                    <!-- Contenedor para los dispositivos -->
                    <div id="devices-container">
                        <!-- Los dispositivos se agregarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const dispositivos = {
        'Delegación Principal': '192.160.141.37',
        'Almacén': '192.160.169.230',
        'San Felipe': '192.165.240.253',
        'Los Algodones': '192.165.232.253',
        'Tecate': '192.165.171.253',
        'EBDI 60': '192.161.192.253'
        };

document.addEventListener('DOMContentLoaded', function() {
const startButton = document.getElementById('startProcess');
const devicesContainer = document.getElementById('devices-container');
let currentDevice = '';

// Crear contenedores para cada dispositivo
for (const [location, ip] of Object.entries(dispositivos)) {
const deviceHtml = `
<div class="device-progress mb-3">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <small class="text-muted">${location}</small>
        <small class="progress-text" id="progress-text-${location}"></small>
    </div>
    <div class="progress" style="height: 20px;">
        <div id="progress-bar-${location}" class="progress-bar progress-bar-striped progress-bar-animated"
            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            0%
        </div>
    </div>
</div>
`;
devicesContainer.insertAdjacentHTML('beforeend', deviceHtml);
}

function updateProgress(location, percentage, text = '') {
requestAnimationFrame(() => {
const progressBar = document.getElementById(`progress-bar-${location}`);
const progressText = document.getElementById(`progress-text-${location}`);

if (progressBar && progressText) {
progressBar.style.width = `${percentage}%`;
progressBar.textContent = `${percentage}%`;
progressBar.setAttribute('aria-valuenow', percentage);

if (text) {
progressText.textContent = text;
}
}
});
}

function handleDeviceCompletion(location, success = true) {
const progressBar = document.getElementById(`progress-bar-${location}`);
if (progressBar) {
progressBar.classList.remove('progress-bar-animated');
progressBar.classList.add(success ? 'bg-success' : 'bg-info');
updateProgress(location, 100, success ? 'Completado' : 'Sin registros nuevos');
}
}

function resetProgress() {
Object.keys(dispositivos).forEach(location => {
const progressBar = document.getElementById(`progress-bar-${location}`);
if (progressBar) {
progressBar.classList.remove('bg-success', 'bg-info');
progressBar.classList.add('progress-bar-animated');
updateProgress(location, 0, '');
}
});
}

startButton.addEventListener('click', function() {
startButton.disabled = true;
resetProgress();
currentDevice = '';
console.clear(); // Limpiar consola al iniciar

const eventSource = new EventSource('{{ url("biometrico/execute") }}');

eventSource.onmessage = function(event) {
try {
const data = JSON.parse(event.data);
if (data.output) {
// Log en consola del navegador
console.log(data.output);

// Detectar cambio de dispositivo actual
Object.keys(dispositivos).forEach(location => {
if (data.output.includes(`Descargando registros de ${location}`)) {
currentDevice = location;
updateProgress(location, 0, 'Iniciando...');
console.log('%cProcesando: ' + location, 'color: blue; font-weight: bold');
}
});

// Actualizar progreso del dispositivo actual
if (currentDevice) {
const progressMatch = data.output.match(/(\d+)\/(\d+)/);
if (progressMatch) {
const [_, current, total] = progressMatch;
const percentage = Math.round((current / total) * 100);
updateProgress(currentDevice, percentage, `${current}/${total} registros`);
console.log(`%cProgreso ${currentDevice}: ${percentage}%`, 'color: green');
}

// Verificar finalización del dispositivo actual
if (data.output.includes(currentDevice)) {
if (data.output.includes('Se insertaron')) {
handleDeviceCompletion(currentDevice, true);
console.log(`%cCompletado ${currentDevice}`, 'color: green; font-weight: bold');
} else if (data.output.includes('No hay registros nuevos')) {
handleDeviceCompletion(currentDevice, false);
console.log(`%cSin registros ${currentDevice}`, 'color: orange; font-weight: bold');
}
}
}
}
} catch (e) {
console.error('Error procesando datos:', e);
}
};

eventSource.onerror = function() {
console.log('%cConexión cerrada', 'color: red');
eventSource.close();
startButton.disabled = false;
};
});
});
</script>
@endsection
