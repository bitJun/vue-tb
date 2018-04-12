import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        info:{}
    },
    mutations: {
        [types.GET_LLPAY_INFO] (state, payload) {
            state.info = payload.data
        },
    },
    actions: {
        postSmssendRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postSmssend, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        postSmscheckRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postSmscheck, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        postOpensmsunituserRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postOpensmsunituser, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        getLlpayInfoRequest: ({commit,dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.get(api.getLlpayInfo)
                    .then(response => {
                        commit({
                            type: types.GET_LLPAY_INFO,
                            data: response.data
                        });
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        getSingleUserQuery: ({commit,dispatch}) => {
            return new Promise((resolve, reject) => {
                axios.get(api.getSingleUser)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        postPwdAuthRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postPwdAuth, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        putUnitUserRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.put(api.putUnitUser, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        uploadUnitPhotoRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.uploadUnitPhoto, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        putUnitUserAcctRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.put(api.putUnitUserAcct, formData)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
    }
}