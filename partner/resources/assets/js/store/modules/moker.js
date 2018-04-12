import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../mutation-types';

export default {
  state: {
    mokerdata: [],
    count: 0
  },
  mutations: {
    [types.GET_MOKER] (state, payload) {
      state.mokerdata = payload.data.data;
      state.count = payload.data.total;
    }
  },
  actions: {
    mokerRequest: ({commit, dispatch}, page) => {
      let url = api.getMokerList+'?limit='+page.limit+'&offset='+page.offset;
      if (page.name) {
        url += '&name='+page.name;
      }
      if (page.mobile) {
        url += '&mobile='+page.mobile;
      }
      axios.get(url)
        .then(response => {
          commit({
            type: types.GET_MOKER,
            data: response.data
          })
        })
        .catch(error => {

        })
    },
    mokerDetailRequest: ({commit, dispatch}, data) => {
        let apiUrl = api.getMokerDetail.replace('{id}', data.id);
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