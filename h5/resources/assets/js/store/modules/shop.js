import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        shop:{},
    },
    mutations: {
        [types.GET_SHOP] (state, payload) {
            state.shop = payload.data;
        },
    },
    actions: {
        getShopRequest: ({commit, dispatch}, token) => {
            const apiUrl = api.getShop.replace('{token}', token);
            return new Promise((resolve, reject) => {
                axios.get(apiUrl)
                    .then(response => {
                        commit({
                            type: types.GET_SHOP,
                            data: response.data
                        })
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        }
    }
}