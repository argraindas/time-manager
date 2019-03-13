import {Ziggy} from '../ziggy';
import route from '../../../vendor/tightenco/ziggy/src/js/route';

export default {
    methods: {
        route: function(name, params, absolute){
            return route(name, params, absolute, Ziggy);
        }
    }
}
