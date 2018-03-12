import Router from 'vue-router'
import Login from '../components/Login'
import UserArea from '../components/UserArea'
import Campaign from '../components/UserArea/Campaign'
import CampaignCreate from '../components/UserArea/Campaign/Create'

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/admin',
      name: 'Login',
      component: Login
    },
    {
      path: '/admin/userarea',
      component: UserArea,
      children: [
        { path: '', redirect: '/admin/userarea/campaign' },
        { path: 'campaign', component: Campaign },
        { path: 'campaign/create', component: CampaignCreate }
      ]
    }

  ]
})

export default router
