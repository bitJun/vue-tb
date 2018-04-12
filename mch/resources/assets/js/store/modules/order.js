import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        orderData:[],
        count:0,
        dayIncome:'0.00'
    },
    mutations: {
        [types.GET_ORDERS] (state, payload) {
            state.orderData = payload.data.data;
            state.count = payload.data._count;
            state.dayIncome = payload.data.dayIncome;
        },
    },
    actions: {
        getOrders: ({commit, dispatch}, page) => {
            let url = api.getOrders+'?limit='+page.limit+'&offset='+page.offset;
            if(page.order_sn){
                url += '&order_sn='+page.order_sn;
            }
            if(page.mobile){
                url += '&mobile='+page.mobile;
            }
            if(page.status){
                url += '&status='+page.status;
            }
            if(page.type){
                url += '&type='+page.type;
            }
            axios.get(url)
                .then(response => {
                    commit({
                        type: types.GET_ORDERS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        },
        getOrderRequest: ({commit, dispatch}, id) => {
            return new Promise((resolve, reject) => {
                const apiUrl = api.getOrder.replace('{id}', id);
                axios.get(apiUrl)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
    }
}