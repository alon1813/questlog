import './bootstrap'; // Carga Axios y la configuración CSRF
import axios from "axios";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

//import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();