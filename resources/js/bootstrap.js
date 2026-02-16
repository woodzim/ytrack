import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import jquery from 'jquery';

// Import Global Libraries
import Choices from 'choices.js';
import ApexCharts from 'apexcharts';
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet-gesture-handling';
import 'leaflet-fullscreen';
import select2 from 'select2';

window.$ = window.jQuery = jquery;
window.Choices = Choices;
window.ApexCharts = ApexCharts;
window.L = L;
select2(); // Initialize select2 on jQuery

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
});
