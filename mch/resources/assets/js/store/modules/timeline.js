import * as api from './../../config';
import * as types from './../mutation-types';

export default {
    state: {
        timelines:[],
        count:0
    },
    mutations: {
        [types.GET_TIMELINES] (state, payload) {
            state.timelines = payload.data.data;
            state.count = payload.data._count;
        },
    },
    actions: {
        getTimelines: ({commit, dispatch}, page) => {
            axios.get(api.getTimelines+'?limit='+page.limit+'&offset='+page.offset)
                .then(response => {
                    commit({
                        type: types.GET_TIMELINES,
                        data: response.data
                    })
                })
                .catch(error => {

                })
        },
        getTimelineRequest: ({commit, dispatch}, id) => {
            return new Promise((resolve, reject) => {
                const apiUrl = api.getTimeline.replace('{id}', id);
                axios.get(apiUrl)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        addTimelineRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                axios.post(api.postTimeline, formData)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject();
                    });
            });
        },
        editTimelineRequest: ({dispatch}, formData) => {
            const apiUrl = api.editTimeline.replace('{id}', formData.id);
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
        deleteTimelineRequest: ({dispatch}, formData) => {
            const apiUrl = api.editTimeline.replace('{id}', formData.id);
            return new Promise((resolve, reject) => {
                axios.delete(apiUrl, formData)
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