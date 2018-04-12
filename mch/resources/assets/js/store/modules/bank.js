import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        banks:[],
        bankCards:[]
    },
    mutations: {
        [types.GET_BANKS] (state, payload) {
            state.banks = payload.data;
        },
        [types.GET_BANKCARDS] (state, payload) {
            state.bankCards = payload.data;
        },
    },
    actions: {
        getBanks: ({commit, dispatch}) => {
            axios.get(api.getBanks)
                .then(response => {
                    commit({
                        type: types.GET_BANKS,
                        data: response.data
                    });
                })
                .catch(error => {

                })
        },
        getBanksRequest: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.get(api.getBankCards)
                    .then(response => {
                        commit({
                            type: types.GET_BANKCARDS,
                            data: response.data
                        });
                        resolve(response.data);
                    })
                    .catch(error => {

                    });
            })
        },
        getBankRequest: ({commit, dispatch}, id) => {
            return new Promise((resolve, reject) => {
                const apiUrl = api.getBankCard.replace('{id}', id);
                axios.get(apiUrl)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        addBankRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.addBankCard, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
        editBankRequest: ({dispatch}, formData) => {
            const apiUrl = api.editBankCard.replace('{id}', formData.id);
            return new Promise((resolve, reject) => {
                axios.put(apiUrl, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
        deleteBankRequest: ({dispatch}, formData) => {
            const apiUrl = api.deleteBankCard.replace('{id}', formData.id);
            return new Promise((resolve, reject) => {
                axios.delete(apiUrl, formData)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
        bankcardAuthVerfyRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postBankcardAuthVerfy, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject();
                    });
            });
        }
    }
}