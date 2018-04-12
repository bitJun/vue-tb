<template>
    <div class="city_view">
        <div class="operates clearfix">
            <a class="btn pull-left" @click="cancel()">取消</a>
            <a class="btn pull-left" @click="sure()">确定</a>
        </div>
        <div class="city_select clearfix">
            <mt-picker ref="status" :slots="province"  value-key="name" :visible-item-count="5" @change="onprovinceChange" class="pull-left" v-bind:style="{'width':'33.33%'}">
            </mt-picker>
            <mt-picker ref="status" :slots="citys"  value-key="name" :visible-item-count="5" @change="oncitysChange" class="pull-left" v-bind:style="{'width':'33.33%'}">
            </mt-picker>
            <mt-picker ref="status" :slots="districts"  value-key="name" :visible-item-count="5" @change="ondistrictChange" class="pull-left" v-bind:style="{'width':'33.33%'}">
            </mt-picker>
        </div>
    </div>
</template>
<script>
let $self = ''
import {mapState} from 'vuex'
export default {
    name: 'city',
    data () {
        return {
            province: [
                {
                    flex: 1,
                    defaultIndex: 0,
                    values: [
                        {
                            id: 110000,
                            listorder: 1,
                            name: "北京",
                            parent_id: 1
                        }
                    ],
                    className: 'province',
                }
            ],
            citys: [
                {
                    flex: 1,
                    defaultIndex: 0,
                    values: [
                        {
                            id: 110100,
                            listorder: 1,
                            name: "北京市",
                            parent_id: 110000
                        }
                    ],
                    className: 'province',
                }
            ],
            districts: [
                {
                    flex: 1,
                    defaultIndex: 0,
                    values: [
                        {
                            id: 110101,
                            listorder: 3,
                            name: "东城区",
                            parent_id: 110100
                        }
                    ],
                    className: 'province',
                }
            ],
            province_name: '',
            city_name: '',
            district_name: ''
        }
    },
    created () {
        $self = this
        $self.init()
    },
    methods: {
        init () {
            $self.$store.dispatch('regionsRequest', {id:1})
                .then((res) => {
                    this.province[0].values = res.data
                    let parent_id = res.data[0].id
                })
                .catch((error) => {
                    $self.loading = false;
                });
        },
        city (id) {
            $self.$store.dispatch('regionsRequest', {id:id})
                .then((res) => {
                    $self.citys[0].values = res.data
                    let parent_id = res.data[0].id
                })
                .catch((error) => {
                    //$self.loading = false;
                });
        },
        district (id) {
            $self.$store.dispatch('regionsRequest', {id:id})
                .then((res) => {
                    $self.districts[0].values = res.data
                    let parent_id = res.data[0].id
                })
                .catch((error) => {
                    //$self.loading = false;
                });
        },
        onprovinceChange (picker, values) {
            let json = picker.getValues()[0];
            $self.city(json.id);
            $self.$parent.data.province_id = json.id;
            $self.province_name = json.name;
        },
        oncitysChange (picker, values) {
            let json = picker.getValues()[0];
            $self.district(json.id);
            $self.$parent.data.city_id = json.id;
            $self.city_name = json.name;
        },
        ondistrictChange (picker, values) {
            let json = picker.getValues()[0];
            $self.$parent.data.district_id = json.id;
            $self.district_name = json.name;
        },
        sure () {
            $self.$parent.address = $self.province_name + '-' + $self.city_name + '-' + $self.district_name;
            $self.$parent.model = '';
            $self.$parent.$parent.ischoose = false
        },
        cancel () {
            $self.$parent.model = '';
            $self.$parent.$parent.ischoose = false 
        }
    }
}
</script>