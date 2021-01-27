import Vue from 'vue';

Vue.component(
    'weather-form',
    require('./components/WeatherForm.vue').default
);

$( document ).ready(function() {
    new Vue({ el: '#weather' });
});
