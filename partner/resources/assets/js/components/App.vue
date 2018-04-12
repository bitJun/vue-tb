<template>
	<div class="parnter" id="partner">
		<TopBar v-if="flag == true"></TopBar>
		<router-view></router-view>
	</div>
</template>
<script>
import TopBar from './common/top.vue'
export default {
	name: 'app',
	data () {
		return {
			flag: true,
			height: 0,
			to: '/'
		}
	},
	mounted () {
		this.$Loading.finish();
	},
	created () {
		this.$Loading.start();
		this.$router.beforeEach((to, from, next) => {
			this.$Loading.start();
				next()
		})
		this.$router.afterEach((to, from) => {
			this.$Loading.finish();
		})
		if (this.$route.name === 'login') {
      this.flag = false
		}
	},
	components: {
		TopBar
	},
	watch: {
		'$route' (to, from) {
			this.to = to.path.split('/')[1]
			if (to.name !== 'login' || to.name !== 'register' || to.name !== 'forgotpwd') {
				this.flag = true
			}
			if (to.name === 'login' || to.name === 'register' || to.name === 'forgotpwd') {
				this.flag = false
			}
	  }
	}
}
</script>
