import * as api from './../../config';

export default {
    actions: {
        getCreditRuleRequest: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                const apiUrl = api.getCreditRule;
                axios.get(apiUrl)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        editCreditRuleRequest: ({dispatch}, formData) => {
            const apiUrl = api.editCreditRule;
            return new Promise((resolve, reject) => {
                axios.put(apiUrl, formData)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
    }
}