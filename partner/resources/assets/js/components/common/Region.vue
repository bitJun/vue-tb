<template>
    <div class="btn-group region-picker">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" @click="display = !display">
            <span v-text="labelData">请选择区域</span> <span class="caret"></span>
        </button>
        <div class="region-picker-drop dropdown-menu" v-if="display">
            <ul class="region-picker-tab">
                <li v-for="item in tabsData" v-text="item.name" :class="{active:item.active}" @click="selectTab(item.keyword)"></li>
            </ul>
            <ul class="region-picker-panel clearfix">
                <li v-for="item in regions" v-text="item.name" :class="{active:item.id == selectedRegionItem.id}" @click="selectRegion(item)"></li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        tabs: {
            type: Array,
            default: function() {
                return[
                    {
                        keyword:"province",
                        name:'省份',
                        active:true
                    },
                    {
                        keyword:"city",
                        name:'城市',
                        active:false
                    },
                    {
                        keyword:"district",
                        name:'县区',
                        active:false
                    }
                ]
            }
        },
        regionsId: {
            type: Object
        }
    },
    data() {
        return {
            display: false,
            tabsData: this.tabs,
            selectedTab: 'province',
            regions: [],
            province: {id:0,name:''},
            city: {id:0,name:''},
            district:{id:0,name:''},
            selectedRegionItem:{id:0,name:''},
            labelData: '',
            selectedRegion: [],
            areaStart: 0
        }
    },
    created () {
        this.init()
    },
    methods:{
        init () {
            let json = this.regionsId
            if (json.province_id != 0) {
                this.province = {
                    id: json.province_id,
                    name: json.province_name
                }
                if (json.city_id != 0 ) {
                    this.city = {
                        id: json.city_id,
                        name: json.city_name
                    }
                    for (let i in this.tabsData) {
                        this.tabsData[i].active = false;
                    }
                    this.tabsData[2].active = true;
                    this.areaStart = 2
                    this.selectedTab = 'district';
                    this.getRegions(json.city_id);
                }
                else {
                    for (let i in this.tabsData) {
                        this.tabsData[i].active = false;
                    }
                    this.tabsData[1].active = true;
                    this.areaStart = 1
                    this.selectedTab = city;
                    this.getRegions(json.province_id)
                }
                this.labelData = json.province_name + json.city_name + json.district_name;
            }
            else {
                this.getRegions(1)
                this.labelData = '请选择区域'
            }
        },
        selectTab: function(tabKeyword) {
            switch (tabKeyword) {
                case 'province':
                    this.selectedTab = tabKeyword
                    for (let i in this.tabsData) {
                        if(this.tabsData[i].keyword == this.selectedTab) {
                            this.tabsData[i].active = true;
                        } else {
                            this.tabsData[i].active = false;
                        }
                    }
                    this.getRegions(1)
                    this.selectedRegionItem.id = this.province.id
                break;
                case 'city':
                    this.selectedTab = tabKeyword
                    for (let i in this.tabsData) {
                        if(this.tabsData[i].keyword == this.selectedTab) {
                            this.tabsData[i].active = true;
                        } else {
                            this.tabsData[i].active = false;
                        }
                    }
                    this.getRegions(this.province.id);
                    this.selectedRegionItem.id = this.city.id
                break;
                case 'district':
                    this.selectedTab = tabKeyword
                    for (let i in this.tabsData) {
                        if(this.tabsData[i].keyword == this.selectedTab) {
                            this.tabsData[i].active = true;
                        } else {
                            this.tabsData[i].active = false;
                        }
                    }
                    this.getRegions(this.city.id);
                    this.selectedRegionItem.id = this.district.id
                break;
            }
        },
        selectRegion: function(item) {
            if (this.selectedTab === 'province') {
                this.province.id = item.id;
                this.province.name = item.name;
                this.selectedTab = 'city'
                for (let i in this.tabsData) {
                    if(this.tabsData[i].keyword == this.selectedTab) {
                        this.tabsData[i].active = true;
                    } else {
                        this.tabsData[i].active = false;
                    }
                }
                this.getRegions(item.id);
                return false;
            }
            if (this.selectedTab === 'city') {
                this.city.id = item.id;
                this.city.name = item.name;
                this.selectedTab = 'district'
                for (let i in this.tabsData) {
                    if(this.tabsData[i].keyword == this.selectedTab) {
                        this.tabsData[i].active = true;
                    } else {
                        this.tabsData[i].active = false;
                    }
                }
                this.getRegions(item.id);
                return false;
            }
            if (this.selectedTab === 'district') {
                this.district.id = item.id;
                this.district.name = item.name;
                this.labelData = this.province.name + '-' + this.city.name + '-' + this.district.name;
                this.selectedRegion = [this.province.id, this.city.id, this.district.id];
                this.$emit('input', this.selectedRegion);
                this.display = !this.display;
            }
        },
        getRegions (id) {
            this.$store.dispatch('getRegions', {id:id})
            .then((response) => {
                this.regions = response;
            })
            .catch((error) => {
                this.loading = false;
            });
        }
    },
    watch: {
        value (val) {
            console.log('value updated: ' + JSON.stringify(val))
        }
    }
}
</script>