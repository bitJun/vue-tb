import * as api from './../../config';

export default {
    state: {

    },
    mutations: {
    },
    actions: {

        getMemberLevelRequest:({commit, dispatch}) => {
            const apiUrl = api.getMemberLevel;
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
        editMemberLevelRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.editMemberLevel, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }
    }
}