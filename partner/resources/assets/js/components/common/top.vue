<template>
	<div class="topbar_view ">
		<Menu mode="horizontal" :theme="theme1" active-name="1" class="header-wrapper" :active-name="this.$route.meta.activeTopMenu" @on-select="routerLink">
			<div class="logo">
      </div>
			<div class="layout-nav">
				<MenuItem v-for="item in route" :key="item.index" v-bind:name="item.link">
					<Icon :type="item.icon"></Icon>
					{{item.name}}
				</MenuItem>
			</div>
			<Dropdown class="layout-profile" placement="bottom-end" @on-click="logout()">
				<a href="javascript:void(0)" style="color:#decbab">
					<!-- <img class="avatar size34 img-circle" :src="user.shop.logo"> -->
					<a style="color:#decbab;">{{user.mobile}}</a>
					<Icon type="arrow-down-b"></Icon>
				</a>
				<Dropdown-menu slot="list">
						<Dropdown-item>退出</Dropdown-item>
				</Dropdown-menu>
			</Dropdown>
		</Menu>
	</div>
</template>
<script>
import {mapState} from 'vuex';
export default {
	name: 'top',
	props: {
		data: {
			type: String
		}
	},
	data () {
		return {
			route: [
				{
					name: '首页',
					link: 'index',
					icon: 'home',
					index: 1
				},
				{
					name: '服务商',
					link: 'Facilitator',
					icon: 'person-stalker',
					index: 2
				},
				{
					name: '商家',
					link: 'businesslist',
					icon: 'ios-people',
					index: 3
				},
				{
					name: '魔客',
					link: 'MokerList',
					icon: 'ios-person',
					index: 4
				},
				{
					name: '佣金',
					link: 'TradeList',
					icon: 'cash',
					index: 5
				},
				{
					name: '设置',
					link: 'setting_account',
					icon: 'ios-gear',
					index: 6
				}
			],
			theme1: 'dark'
		}
	},
	created() {
		this.$store.dispatch('setAuthUser');
	},
	computed: {
		...mapState({
			user: state => state.authUser
		}),
	},
	methods: {
		logout() {
			this.$store.dispatch('logoutRequest')
				.then(() => {
					this.$router.push({path: '/login'});
				});
		},
		routerLink(name) {
      this.$router.push({ name: name });
    }
	}
}
</script>