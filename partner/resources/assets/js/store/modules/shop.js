import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../mutation-types';

export default {
  state: {
    shopdata: [],
    count: 0
  },
  mutations: {
    [types.GET_SHOP] (state, payload) {
      state.shopdata = payload.data.data;
      state.count = payload.data._count;
    }
  },
  actions: {
    shopRequest: ({commit, dispatch}, page) => {
      let url = api.getshops+'?limit='+page.limit+'&offset='+page.offset;
      if(page.name){
        url += '&name='+page.name;
      }
      if(page.contact){
        url += '&contact='+page.contact;
      }
      if(page.tel){
        url += '&tel='+page.tel;
      }
      axios.get(url)
        .then(response => {
          commit({
            type: types.GET_SHOP,
            data: response.data
          })
        })
        .catch(error => {

        })
    },
    addshopRequest: ({dispatch}, formData) => {
      return new Promise((resolve, reject) => {
        axios.post(api.postshop, formData)
          .then(response => {
            resolve();
          })
          .catch(error => {
            reject();
          });
      });
    },
    getShopDetail:({comit, dispatch}, page) => {
      let url = api.getshopdetail.replace('{id}', page.id);
      return new Promise((resolve, reject) => {
        axios.get(url)
          .then(response => {
            resolve(response.data);
          })
          .catch(error => {
            reject(error);
          });
        })
    },
    editShopRequest:({comit, dispatch}, page) => {
      let url = api.getshopdetail.replace('{id}', page.id);
      return new Promise((resolve, reject) => {
        axios.put(url,page)
          .then(response => {
            resolve(response.data);
          })
          .catch(error => {
            reject(error);
          });
        })
    },
    getTags:({comit, dispatch}) => {
      return new Promise((resolve, reject) => {
        axios.get(api.gettags)
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