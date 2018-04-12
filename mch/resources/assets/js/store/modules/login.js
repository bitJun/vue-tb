import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../mutation-types';

export default {
    state: {
        errors: {
            username: null,
            password: null,
            captcha: null,
            error: null
        }
    },
    mutations: {
        [types.LOGIN_SUCCESS] (state, payload) {
            state.errors.username = null;
            state.errors.password = null;
            state.errors.captcha = null;
            state.errors.error = null;
        },
        [types.LOGIN_FAILURE] (state, payload) {
            state.errors.username = payload.errors.username ? payload.errors.username[0] : null;
            state.errors.password = payload.errors.password ? payload.errors.password[0] : null;
            state.errors.captcha = payload.errors.captcha ? payload.errors.captcha[0] : null;
            state.errors.error = payload.errors.error ? payload.errors.error : null;
        },
        [types.CLEAR_LOGIN_ERRORS] (state, payload) {
            state.errors.username = null;
            state.errors.password = null;
            state.errors.captcha = null;
            state.errors.error = null;
        }
    },
    actions: {
        loginRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.login, formData)
                    .then(response => {
                        dispatch('loginSuccess', response.data);
                        resolve();
                    })
                    .catch(error => {
                        dispatch('loginFailure', error.response.data);
                        reject();
                    });
            });
        },
        loginSuccess: ({commit, dispatch}, jwtTokenObj) => {
            jwtToken.setToken(jwtTokenObj.token);

            commit({
                type: types.LOGIN_SUCCESS
            });

            dispatch('setAuthUser', jwtTokenObj.user);
        },
        loginFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.LOGIN_FAILURE,
                errors: body
            });
        },
        clearLoginErrors: ({commit}) => {
            commit({
                type: types.CLEAR_LOGIN_ERRORS
            });
        },
        logoutRequest: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.post(api.logout)
                    .then(response => {
                        jwtToken.removeToken();
                        dispatch('unsetAuthUser');
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        }
    }
}