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
        WithdrawalsRequest:({comit, dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.get(api.Withdrawals)
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
            })
        },
        EditWithdrawalsRequest:({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.Withdrawals, formData)
                .then(response => {
                    resolve();
                })
                .catch(error => {
                    reject(error);
                });
            })
        },
        BankListRequest:({comit, dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.get(api.BankList)
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