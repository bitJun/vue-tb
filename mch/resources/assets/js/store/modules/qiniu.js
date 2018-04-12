import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        token:''
    },
    mutations: {
        [types.GET_QINIUTOKEN] (state, payload) {
            state.token = payload.data.token;
            console.log('token=', state.token);
        },
    },
    actions: {
        getQiniuToken: ({commit, dispatch}) => {
            axios.get(api.getQiniuToken)
                .then(response => {
                    commit({
                        type: types.GET_QINIUTOKEN,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}