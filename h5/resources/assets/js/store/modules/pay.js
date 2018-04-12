import * as api from './../../config';

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        getOrderRequest: ({commit, dispatch} , data) => {
            let apiUrl = api.getOrder.replace('{sid}', data.sid);
            apiUrl = apiUrl.replace('{osn}', data.osn);
            return new Promise((resolve, reject) => {
                axios.get(apiUrl)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        postOrderRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.postOrder;
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