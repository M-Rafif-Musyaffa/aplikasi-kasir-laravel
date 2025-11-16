import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';

// Impor Chart.js
import { Chart } from 'chart.js/auto';

// Chart.js
window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();
