import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        memberData:[],
        count:0
    },
    mutations: {
        [types.GET_MEMBERS] (state, payload) {
            state.memberData = payload.data.data;
            state.count = payload.data._count;
        },
    },
    actions: {
        getMembers: ({commit, dispatch}, page) => {
            let url = api.getMembers+'?limit='+page.limit+'&offset='+page.offset;
            if(page.mobile){
                url += '&mobile='+page.mobile;
            }
            if(page.level){
                url += '&level='+page.level;
            }
            axios.get(url)
                .then(response => {
                    commit({
                        type: types.GET_MEMBERS,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}