import * as api from './../../config';

export default {
    state: {

    },
    mutations: {
    },
    actions: {
        editPasswordRequest: ({dispatch}, formData) => {
            const apiUrl = api.editPassword;
            return new Promise((resolve, reject) => {
                axios.put(apiUrl, formData)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
    }
}