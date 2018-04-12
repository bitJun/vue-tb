<template>
    <div class="container">
        <Row>
            <Col span="24">
                <Button type="primary" :loading="loading" @click="addTimeline">
                    <span v-if="!loading">发布商圈</span>
                    <span v-else>发布中...</span>
                </Button>
            </Col>
        </Row>
        <br>
        <Card class="f12" style="margin-bottom:20px;" v-for="(timeline, index) in timelines" :key="index">
            <p slot="title" v-text="timeline.created_at">
                <Icon type="ios-time-outline"></Icon>
            </p>
            <a href="#" slot="extra" @click.prevent="edit(timeline.id)">
                <Icon type="edit"></Icon>
                编辑
            </a>
            <span slot="extra" class="delimiter">|</span>
            <Poptip
                    slot="extra"
                    placement="top-end"
                    confirm
                    title="确认删除？"
                    @on-ok="deleteTimeline(timeline.id)">
                <a href="javascript:void(0)">
                    <Icon type="trash-a"></Icon>
                    删除
                </a>
            </Poptip>
            <Row :gutter="10">
                <Col span="10"><p>{{ timeline.content }}</p><p>{{ timeline.link }}</p></Col>
                <Col span="14">
                    <div class="demo-upload-list" v-for="img in timeline.imgs">
                            <img :src="img.thumb">
                    </div>
                </Col>
            </Row>
        </Card>
        <div v-if="!timelines" class="well well-lg text-center">
            <Icon type="document"></Icon> 暂无数据
        </div>
        <div v-if="timelines" style="margin: 10px;overflow: hidden">
            <div style="float: right;">
                <Page show-total show-elevator size="small" @on-change="changePage" :page-size="10" :total="count" :current="1"></Page>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                loading: false,
                limit:10,
                offset:0,
            }
        },
        created() {
            this.$store.dispatch('getTimelines', {limit:this.limit, offset:this.offset});
        },
        computed: {
            ...mapState({
                timelines: state => state.timeline.timelines,
                count: state => state.timeline.count
            })
        },
        mounted () {

        },
        methods:{
            changePage: function(page){
                this.offset = (page-1)*this.limit;
                this.$store.dispatch('getTimelines', {limit:this.limit, offset:this.offset});
            },
            edit: function(id) {
                this.$router.push({ path: '/timeline/edit/'+id });
            },
            addTimeline: function(){
                this.loading = true;
                this.$router.push({ path: '/timeline/add' });
            },
            deleteTimeline (id) {
                this.$store.dispatch('deleteTimelineRequest',{id:id})
                    .then((response) => {
                        this.$Message.success('删除成功');
                        this.$store.dispatch('getTimelines', {limit:this.limit, offset:this.offset});
                    })
                    .catch((error) => {
                    });
            }
        }
    }
</script>