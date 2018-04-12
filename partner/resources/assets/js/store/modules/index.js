import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../mutation-types';

export default {
  state: {
    jsondata: []
  },
  mutations: {
    [types.GET_INDEX] (state, payload) {
      state.jsondata = payload.data;
    }
  },
  actions: {
    indexRequest: ({commit, dispatch}, page) => {
      let url = api.Index;
      axios.get(url)
        .then(response => {
          commit({
            type: types.GET_INDEX,
            data: response.data
          })
        })
        .catch(error => {

        })
    }
  }
}