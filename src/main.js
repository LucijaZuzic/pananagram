import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)

Vue.use(VueRouter)

import Create from './create.vue'
import Solve from './solve.vue'
import Victory from './victory.vue'
import Update from './update.vue'
import PuzzleList from './puzzlelist.vue'
import LogIn from './login.vue'
import PuzzleInfo from './puzzleinfo.vue'
import UserInfo from './userinfo.vue'
import Register from './register.vue'
import SearchUser from './searchuser.vue'
import FriendInfo from './friendinfo.vue'
import FriendList from './friendlist.vue'
import RequestInfo from './requestinfo.vue'
import BlockedInfo from './blocked.vue'

const routes = [
  { path: '/', redirect: '/puzzle_list' },
  { path: '/create', component: Create, name: 'create' },
  { path: '/user_info', component: UserInfo, name: 'user_info' },
  { path: '/solve/:id', component: Solve, name: 'solve' },
  { path: '/victory', component: Victory, name: 'victory' },
  { path: '/update/:id', component: Update, name: 'update' },
  { path: '/puzzle_info/:id', component: PuzzleInfo, name: 'puzzle_info' },
  { path: '/puzzle_list', component: PuzzleList, name: 'puzzle_list' },
  { path: '/log_in', component: LogIn, name: 'log_in' },
  { path: '/register', component: Register, name: 'register' },
  { path: '/search_user', component: SearchUser, name: 'search_user' },
  { path: '/friend_info/:username', component: FriendInfo, name: 'friend_info' },
  { path: '/friend_list', component: FriendList, name: 'friend_list' },
  { path: '/request_info', component: RequestInfo, name: 'request_info' },
  { path: '/blocked_info', component: BlockedInfo, name: 'blocked_info' },
]
const router = new VueRouter({
  routes
})

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
