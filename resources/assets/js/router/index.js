import Router from 'vue-router'
import Login from '../components/Login'
import UserArea from '../components/UserArea'
import Campaign from '../components/UserArea/Campaign'
import CampaignCreate from '../components/UserArea/Campaign/Create'
import CampaignEdit from '../components/UserArea/Campaign/Edit'

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
        { path: '', redirect: '/admin/userarea/campaigns' },
        { path: 'campaigns', component: Campaign },
        { path: 'campaigns/create', component: CampaignCreate },
        { path: 'campaigns/:id', component: CampaignEdit }
      ]
    }

  ]
})

export default router
