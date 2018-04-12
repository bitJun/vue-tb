import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        shopdata: [],
        count: 0
    },
    mutations: {
        [types.GET_FACILITATOR] (state, payload) {
            state.shopdata = payload.data.info            ;
            state.count = payload.data.count;
        }
    },
    actions: {
        facilitatorInfo: ({commit, dispatch}, data) => {
            let apiUrl = api.facilitatorInfo;
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
        facilitatorList: ({commit, dispatch}, data) => {
            let apiUrl = api.facilitatorList;
            if(data.hasOwnProperty('id')){
                apiUrl = apiUrl + "?id="+data.id+ "&company_name="+data.company_name+"&name="+data.name+"&mobile="+data.mobile
                    +"&limit="+data.limit+"&offset="+data.offset;
            }else{
                apiUrl = apiUrl+ "?company_name="+data.company_name+"&name="+data.name+"&mobile="+data.mobile + "&limit="+data.limit+"&offset="+data.offset;
            }
            if(data.name){
                apiUrl += '&name='+data.name;
            }
            if(data.company_name){
                apiUrl += '&company_name='+data.company_name;
            }
            if(data.mobile){
                apiUrl += '&mobile='+data.mobile;
            }
            axios.get(apiUrl)
                .then(response => {
                    commit({
                        type: types.GET_FACILITATOR,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        },
        facilitatorShow: ({commit, dispatch}, data) => {
            let apiUrl = api.facilitatorEdit.replace('{id}', data.id);
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
        facilitatorEdit: ({commit, dispatch}, formData) => {
            let apiUrl = api.facilitatorEdit.replace('{id}', formData.id);;
            return new Promise((resolve, reject) => {
                axios.patch(apiUrl,formData)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        facilitatorDelete: ({commit, dispatch}, data) => {
            let apiUrl = api.facilitatorEdit.replace('{id}', data.id);
            return new Promise((resolve, reject) => {
                axios.delete(apiUrl)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        facilitatorStore: ({commit, dispatch}, formData) => {
            let apiUrl = api.facilitatorStore;
            return new Promise((resolve, reject) => {
                axios.post(apiUrl,formData)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        }
    }
}
