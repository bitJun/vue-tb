import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        downloadQrcode:'',
        cashQrcode:''
    },
    mutations: {
        [types.GET_SHOP_QRCODES] (state, payload) {
            state.downloadQrcode = payload.data.download_qrcode;
            state.cashQrcode = payload.data.cash_qrcode;
        },
    },
    actions: {
        getShopQrcodes: ({commit, dispatch}) => {
            axios.get(api.getShopQrcodes)
                .then(response => {
                    commit({
                        type: types.GET_SHOP_QRCODES,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        }
    }
}