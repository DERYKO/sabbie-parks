import * as VueGoogleMaps from "vue2-google-maps";
Nova.booting((Vue, router) => {
    Vue.use(VueGoogleMaps, {
        load: {
            key: "AIzaSyAwB-YqrFP1K_TdPNAJ_DapYcqC4v6FM58",
            libraries: "places" // necessary for places input
        }
    });
    Vue.component('index-mapping-select', require('./components/IndexField'));
    Vue.component('detail-mapping-select', require('./components/DetailField'));
    Vue.component('form-mapping-select', require('./components/FormField'));
})
