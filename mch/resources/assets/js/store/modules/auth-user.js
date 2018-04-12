import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        authenticated: false,
        id: null,
        shop_id: null,
        username: null,
        mobile:null,
        shop: {
            name: '',
            logo: ''
        }
    },
    mutations: {
        [types.SET_AUTH_USER] (state, payload) {
            state.authenticated = true;
            state.id = payload.user.id;
            state.shop_id = payload.user.shop_id;
            state.username = payload.user.username;
            state.mobile = payload.user.mobile
            state.shop = payload.user.shop;
        },
        [types.UNSET_AUTH_USER] (state, payload) {
            state.authenticated = false;
            state.id = null;
            state.shop_id = null;
            state.username = null;
            state.mobile = null;
            state.shop = {name:'',logo:''};
        }
    },
    actions: {
        setAuthUser: ({commit, dispatch}) => {
            axios.get(api.currentUser)
                .then(response => {
                    commit({
                        type: types.SET_AUTH_USER,
                        user: response.data
                    })
                })
                .catch(error => {
                    dispatch('logoutRequest');
                })
        },
        unsetAuthUser: ({commit}) => {
            commit({
                type: types.UNSET_AUTH_USER
            });
        }
    }
}