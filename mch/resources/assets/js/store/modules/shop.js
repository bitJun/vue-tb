import * as api from './../../config';

export default {
    state: {

    },
    mutations: {
    },
    actions: {
        getShopRequest: ({commit, dispatch}) => {
            const apiUrl = api.getShop;
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
        editShopRequest: ({dispatch}, formData) => {
            const apiUrl = api.editShop;
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