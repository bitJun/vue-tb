<template>
    <div v-if="pageCount > 0">
        <ul :class="containerClass" class="pull-left">
            <li class="total">
                当前 {{selected*pageRange+1}}-{{(selected + 1)*pageRange}} 条 共 {{ pageCount }} 条
            </li>
        </ul>
        <ul :class="containerClass">
            <li :class="[firstClass, { disabled: firstPageSelected() }]">
                <a @click="firstPage()" @keyup.enter="firstPage()" :class="firstLinkClass" tabindex="0"><slot name="first">{{ firstText }}</slot></a>
            </li>
            <li :class="[prevClass, { disabled: firstPageSelected() }]">
                <a @click="prevPage()" @keyup.enter="prevPage()" :class="prevLinkClass" tabindex="0"><slot name="prev">{{ prevText }}</slot></a>
            </li>
            <li v-for="page in pages" :class="[pageClass, { active: page.selected, disabled: page.disabled }]">
                <a @click="handlePageSelected(page.index)" @keyup.enter="handlePageSelected(page.index)" :class="pageLinkClass" tabindex="0">{{ page.content }}</a>
            </li>
            <li :class="[nextClass, { disabled: lastPageSelected() }]">
                <a @click="nextPage()" @keyup.enter="nextPage()" :class="nextLinkClass" tabindex="0"><slot name="next">{{ nextText }}</slot></a>
            </li>
            <li :class="[lastClass, { disabled: lastPageSelected() }]">
                <a @click="lastPage()" @keyup.enter="lastPage()" :class="lastLinkClass" tabindex="0"><slot name="last">{{ lastText }}</slot></a>
            </li>
        </ul>
        <ul ng-if="pageCount > pageRange && !showJump" class="pagination pull-right">
            <li class="jump">
                <input type="text" v-model="pageNo" class="form-control input-sm"/>
                <button class="btn btn-default btn-sm" type="button" @click="goPage(pageNo-1)" @keyup.enter="goPage(pageNo-1)">跳转</button>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            pageCount: {
                type: Number,
                required: true
            },
            initialPage: {
                type: Number,
                default: 0
            },
            forcePage: {
                type: Number
            },
            clickHandler: {
                type: Function,
                default: () => { }
            },
            pageRange: {
                type: Number,
                default: 3
            },
            marginPages: {
                type: Number,
                default: 0
            },
            prevText: {
                type: String,
                default: '‹'
            },
            nextText: {
                type: String,
                default: '›'
            },
            firstText: {
                type: String,
                default: '«'
            },
            lastText: {
                type: String,
                default: '»'
            },
            containerClass: {
                type: String
            },
            pageClass: {
                type: String
            },
            pageLinkClass: {
                type: String
            },
            prevClass: {
                type: String
            },
            prevLinkClass: {
                type: String
            },
            nextClass: {
                type: String
            },
            nextLinkClass: {
                type: String
            },
            firstClass: {
                type: String
            },
            firstLinkClass: {
                type: String
            },
            lastClass: {
                type: String
            },
            lastLinkClass: {
                type: String
            },
            showJump: {
                type: Boolean,
                default: true
            },
            showRange: {
                type: Number,
                default: 5
            },
            goPageNo: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                selected: this.initialPage,
                pageNo: this.goPageNo
            }
        },
        beforeUpdate() {
            if (this.forcePage === undefined) return
            if (this.forcePage !== this.selected) {
                this.selected = this.forcePage
            }
        },
        computed: {
            pages: function () {
                let items = {}
                if (this.pageCount <= this.pageRange) {
                    for (let index = 0; index < this.pageCount; index++) {
                        let page = {
                            index: index,
                            content: index + 1,
                            selected: index === this.selected
                        }
                        items[index] = page
                    }
                } else {
                    let leftPart = this.showRange / 2
                    let rightPart = this.showRange - leftPart

                    if (this.selected < leftPart) {
                        leftPart = this.selected
                        rightPart = this.showRange - leftPart
                    } else if (this.selected > this.pageCount - this.showRange / 2) {
                        rightPart = this.pageCount - this.selected
                        leftPart = this.showRange - rightPart
                    }
                    // items logic extracted into this function
                    let mapItems = index => {
                        let page = {
                            index: index,
                            content: index + 1,
                            selected: index === this.selected
                        }

                        if (index <= this.marginPages - 1 || index >= this.pageCount - this.marginPages) {
                            items[index] = page
                            return
                        }

                        let breakView = {
                            content: '...',
                            break: true
                        }
                        if ((this.selected - leftPart) > this.marginPages && items[this.marginPages] !== breakView) {
                            let bindex = this.selected - this.showRange;
                            if(bindex < 0) {
                                bindex = 0;
                            }
                            items[this.marginPages] = {content:breakView.content, break:breakView.break, index:bindex};
                        }

                        if ((this.selected + rightPart) < (this.pageCount - this.marginPages - 1) && items[this.pageCount - this.marginPages - 1] !== breakView) {
                            let bindex = this.selected + this.showRange;
                            if(bindex > this.pageCount - 1) {
                                bindex = this.pageCount - 1;
                            }
                            items[this.pageCount - this.marginPages - 1] = {content:breakView.content, break:breakView.break, index:bindex};
                        }

                        let overCount = this.selected + rightPart - this.pageCount + 1
                        if (overCount > 0 && index === this.selected - leftPart - overCount) {
                            items[index] = page
                        }
                        if ((index >= this.selected - leftPart) && (index <= this.selected + rightPart)) {
                            items[index] = page
                            return
                        }
                    }

                    // 1st - loop thru low end of margin pages
                    for (let i = 0; i < this.marginPages; i++) {
                        mapItems(i);
                    }

                    // 2nd - loop thru high end of margin pages
                    for (let i = this.pageCount - 1; i >= this.pageCount - this.marginPages; i--) {
                        mapItems(i);
                    }

                    // 3rd - loop thru selected range
                    let selectedRangeLow = 0;
                    if (this.selected - this.pageRange > 0) {
                        selectedRangeLow = this.selected - this.pageRange;
                    }

                    let selectedRangeHigh = this.pageCount;
                    if (this.selected + this.pageRange < this.pageCount) {
                        selectedRangeHigh = this.selected + this.pageRange;
                    }

                    for (let i = selectedRangeLow; i < selectedRangeHigh; i++) {
                        mapItems(i);
                    }
                }
                return items
            }
        },
        methods: {
            handlePageSelected(selected) {
                selected = Number(selected);
                if (this.selected === selected) return

                this.selected = selected

                this.clickHandler(this.selected + 1)
            },
            prevPage() {
                if (this.selected <= 0) return

                this.selected--

                this.clickHandler(this.selected + 1)
            },
            nextPage() {
                if (this.selected >= this.pageCount - 1) return

                this.selected++

                this.clickHandler(this.selected + 1)
            },
            firstPage() {
                if (this.selected <= 0) return

                this.selected = 0;

                this.clickHandler(this.selected + 1)
            },
            lastPage() {
                if (this.selected >= this.pageCount - 1) return

                this.selected = this.pageCount - 1;

                this.clickHandler(this.selected + 1)
            },
            firstPageSelected() {
                return this.selected === 0
            },
            lastPageSelected() {
                return (this.selected === this.pageCount - 1) || (this.pageCount === 0)
            },
            goPage(pageNo) {
                pageNo = Number(pageNo);
                if(pageNo < 0) {
                    pageNo = 0;
                } else if(pageNo > this.pageCount - 1) {
                    pageNo = this.pageCount - 1;
                }
                if (this.selected === pageNo) return

                this.selected = pageNo;

                this.clickHandler(this.selected + 1)
            },
        }
    }
</script>
