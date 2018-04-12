import * as api from './../../config';

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        sendVerifyCodeRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.sendVerifyCode;
            return new Promise((resolve, reject) => {
                axios.post(apiUrl, formData)
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