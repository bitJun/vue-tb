import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        
    },
    mutations: {

    },
    actions: {
        regionList: ({commit, dispatch}, data) => {
            let apiUrl = api.regionList;
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
        getRegions: ({commit, dispatch}, data) => {
          let apiUrl = api.getregions.replace('{id}', data.id);
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
