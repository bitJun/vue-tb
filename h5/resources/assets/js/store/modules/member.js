import * as api from './../../config';

export default {
    state: {

    },
    mutations: {

    },
    actions: {
        giveCreditRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.giveCredit;
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
        bindMemberRequest: ({commit, dispatch}, formData) => {
            const apiUrl = api.bindMember;
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
    }
}