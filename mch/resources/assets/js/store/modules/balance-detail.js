import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        balanceDetailData:[],
        count:0
    },
    mutations: {
        [types.GET_BALANCE_DETAILS] (state, payload) {
            state.balanceDetailData = payload.data.data;
            state.count = payload.data._count;
        },
    },
    actions: {
        getBalanceDetails: ({commit, dispatch}, page) => {
            let url = api.getBalanceDetails+'?limit='+page.limit+'&offset='+page.offset;
            if(page.mobile){
                url += '&mobile='+page.mobile;
            }
            if(page.member_id){
                url += '&member_id='+page.member_id;
            }
            if(page.level){
                url += '&level='+page.level;
            }
            axios.get(url)
                .then(response => {
                    commit({
                        type: types.GET_BALANCE_DETAILS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}