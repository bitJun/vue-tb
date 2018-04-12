import * as api from './../../config';

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        regionsRequest: ({commit, dispatch}, data) => {
            let apiUrl = api.mokerRegions.replace('{id}', data.id);
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