import { Datetime } from 'vue-datetime';
import { DateTime } from 'luxon';
import 'vue-datetime/dist/vue-datetime.css';

export default {
    components: {
        datetime: Datetime
    },

    methods: {
        toISO(value) {
            return value ? DateTime.fromSQL(value).toISO() : null;
        },

        toSQL(value) {
            return value ? DateTime.fromISO(value).toFormat('yyyy-LL-dd HH:mm:ss') : null;
        },
    }
}
