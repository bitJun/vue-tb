import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        balanceDetailData:[],
        count:0
    },
    mutations: {
        [types.GET_SHOP_BALANCE_DETAILS] (state, payload) {
            state.balanceDetailData = payload.data.data;
            state.count = payload.data._count;
        },
    },
    actions: {
        getShopBalanceDetails: ({commit, dispatch}, page) => {
            let url = api.getShopBalanceDetails+'?limit='+page.limit+'&offset='+page.offset;
            if(page.type){
                url += '&type='+page.type;
            }
            if(page.order_sn){
                url += '&order_sn='+page.order_sn;
            }
            if(page.date_start){
                url += '&date_start='+page.date_start;
            }
            if(page.date_end){
                url += '&date_end='+page.date_end;
            }
            axios.get(url)
                .then(response => {
                    commit({
                        type: types.GET_SHOP_BALANCE_DETAILS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}