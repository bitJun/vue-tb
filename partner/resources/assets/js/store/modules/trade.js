import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../mutation-types';

export default {
  state: {
    commissionlist: [],
    count: 0
  },
  mutations: {
    [types.GET_TRADE] (state, payload) {
      state.commissionlist = payload.data.data;
      state.count = payload.data.total;
    }
  },
  actions: {
    commissionRequest: ({commit, dispatch}, page) => {
      let url = api.getCommissionlist+'?limit='+page.limit+'&offset='+page.offset;
      url += '&type='+page.type;
      if(page.order_sn){
        url += '&order_sn='+page.order_sn;
      }
      if(page.start){
        url += '&start='+page.start;
      }
      if(page.end){
        url += '&end='+page.end;
      }
      axios.get(url)
        .then(response => {
          commit({
            type: types.GET_TRADE,
            data: response.data
          })
        })
        .catch(error => {

        })
    }
  }
}