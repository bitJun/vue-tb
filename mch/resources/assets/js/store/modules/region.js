import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        regions:{}
    },
    mutations: {
        [types.GET_REGIONS] (state, payload) {
            state.regions = payload.data;
        },
    },
    actions: {
        getRegions: ({commit, dispatch}) => {
            axios.get(api.getRegions)
                .then(response => {
                    commit({
                        type: types.GET_REGIONS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}