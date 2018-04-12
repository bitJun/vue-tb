<template>
    <div class="btn-group region-picker">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" @click="display = !display">
            <span v-text="labelData"></span> <span class="caret"></span>
        </button>
        <div class="region-picker-drop dropdown-menu" v-if="display">
            <ul class="region-picker-tab">
                <li v-for="item in tabsData"
                    v-text="item.name"
                    :class="{active:item.active}"
                    @click="selectTab(item.keyword)"></li>
            </ul>
            <ul class="region-picker-panel clearfix">
                <li v-for="item in regions"
                    v-text="item.name"
                    :class="{active:item.id == selectedRegionItem.id}"
                    @click="selectRegion(item)"></li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        label: {
            type:String,
            default: '请选择'
        },
        startIndex:{
            type: Number,
            default: 1
        },
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
        regionData: {
            type: Object,
            default: function() {
                return {};
            }
        },
        value: {
            type: Array,
            default: function() {
                return [];
            }
        }
    },
    data() {
        return {
            tabsData: this.tabs,
            start: this.startIndex,
            selectedTab: 'province',
            province: {id:0,name:''},
            city: {id:0,name:''},
            district:{id:0,name:''},
            selectedRegionItem:{id:0,name:''},
            selectedRegion:[],
            display: false,
            labelRes: ''
        }
    },
    computed: {
        regions: function() {
            return this.regionData[this.start];
        },
        labelData: function() {
            if(this.labelRes) {
                return this.labelRes;
            } else if(this.value.length > 0) {
                var lab = '';
                for (let i in this.value) {
                    if(i == 0) {
                        lab = this.value[i].name ;
                    } else {
                        lab += '-'+this.value[i].name;
                    }
                }
                return lab;
            }
            return this.label;
        }
    },
    methods:{
        selectTab: function(tabKeyword) {
            if(tabKeyword == 'city' && this.province.id == 0) {
                return false;
            }
            if(tabKeyword == 'district' && this.city.id == 0) {
                return false;
            }
            this.selectedTab = tabKeyword;

            for (let i in this.tabsData) {
                this.tabsData[i].active = false;
            }
            for (let i in this.tabsData) {
                if(this.tabsData[i].keyword == this.selectedTab) {
                    this.tabsData[i].active = true;
                }
            }

            if(this.selectedTab == 'province') {
                this.selectedRegionItem = this.province;
                this.start = 1;
            }
            if(this.selectedTab == 'city') {
                this.selectedRegionItem = this.city;
                this.start = this.province.id;
            }
            if(this.selectedTab == 'district') {
                this.selectedRegionItem = this.district;
                this.start = this.city.id;
            }
        },
        toggleTab: function(tabKeword) {
            this.selectedTab = tabKeword;

            for (let i in this.tabsData) {
                this.tabsData[i].active = false;
            }
            for (let i in this.tabsData) {
                if(this.tabsData[i].keyword == this.selectedTab) {
                    this.tabsData[i].active = true;
                }
            }

            this.start = this.selectedRegionItem.id;
        },
        selectRegion: function(item) {
            let nextTab = '';
            let lastTab = this.tabsData[this.tabsData.length - 1].keyword;
            if(this.selectedTab == 'province') {
                this.selectedRegionItem = item;
                this.province = item;
                this.city = {id:0, name:''};
                this.district = {id:0, name:''};
                nextTab = 'city';
                if(lastTab == this.selectedTab) {
                    this.selectedRegion = [this.province];
                    this.labelRes = this.province.name;
                    this.$emit('input', this.selectedRegion);
                    this.display = false;
                }
            }
            if(this.selectedTab == 'city') {
                this.selectedRegionItem = item;
                this.city = item;
                this.district = {id:0, name:''};
                nextTab = 'district';
                if(lastTab == this.selectedTab) {
                    this.selectedRegion = [this.province, this.city];
                    this.labelRes = this.province.name + '-' + this.city.name;
                    this.$emit('input', this.selectedRegion);
                    this.display = false;
                }
            }
            if(this.selectedTab == 'district') {
                this.selectedRegionItem = item;
                this.district = item;
                if(lastTab == this.selectedTab) {
                    this.selectedRegion = [this.province, this.city, this.district];
                    this.labelRes = this.province.name + '-' + this.city.name + '-' + this.district.name;
                    this.$emit('input', this.selectedRegion);
                    this.display = false;
                }
            }
            if(nextTab) {
                this.toggleTab(nextTab);
            }

            console.log(this.province.name, this.city.name, this.district.name, this.selectedRegion);
        }
    },
    watch: {
        value (val) {
            console.log('value updated: ' + JSON.stringify(val))
        }
    }
}
</script>