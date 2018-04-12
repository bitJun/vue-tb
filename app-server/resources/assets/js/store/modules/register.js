import * as api from './../../config';

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        registerRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.mokerRegister;
            return new Promise((resolve, reject) => {
                axios.post(apiUrl, formData)
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
            })
        },
        postSmsRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.mokerSms;
            return new Promise((resolve, reject) => {
                axios.post(apiUrl, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        getmokerinfoRequest: ({commit, dispatch} , data) => {
            const apiUrl = api.mokerInfo.replace('{id}', data.id);
            return new Promise((resolve, reject) => {
                axios.get(apiUrl)
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
            })
        }
    }
}