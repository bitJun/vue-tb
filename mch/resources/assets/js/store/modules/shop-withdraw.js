import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        withdrawData:[],
        count:0,
        statusData :[]
    },
    mutations: {
        [types.GET_SHOP_WITHDRAWS] (state, payload) {
            state.withdrawData = payload.data.data;
            state.count = payload.data._count;
        },
        [types.GET_SHOP_WITHDRAW_STATUS] (state, payload) {
            state.statusData = payload.data;
        }
    },
    actions: {

        addShopWithdrawRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postShopWithdraw, formData)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
        getShopWithdrawsRequest: ({commit, dispatch}, page) => {
            let url = api.getShopWithdraws+'?limit='+page.limit+'&offset='+page.offset;
            if(page.status){
                url += '&status='+page.status;
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
                        type: types.GET_SHOP_WITHDRAWS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        },
        getShopWithdrawStatusRequest: ({commit, dispatch}) => {
            let url = api.getShopWithdrawStatus;
            axios.get(url)
                .then(response => {
                    commit({
                        type: types.GET_SHOP_WITHDRAW_STATUS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}