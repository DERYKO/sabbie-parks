import * as VueGoogleMaps from "vue2-google-maps";
Nova.booting((Vue, router) => {
    Vue.use(VueGoogleMaps, {
        load: {
            key: "AIzaSyAnRNSn36QJmZmcocAkuAcTjYG_NhmjoNQ",
            libraries: "places" // necessary for places input
        }
    });
    Vue.component('index-mapping-select', require('./components/IndexField'));
    Vue.component('detail-mapping-select', require('./components/DetailField'));
    Vue.component('form-mapping-select', require('./components/FormField'));
})
