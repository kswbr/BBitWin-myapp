import Router from 'vue-router'
import Login from '../components/Login'
import UserArea from '../components/UserArea'
import Dashboard from '../components/UserArea/Dashboard'
import Campaign from '../components/UserArea/Campaign'
import CampaignCreate from '../components/UserArea/Campaign/Create'
import CampaignEdit from '../components/UserArea/Campaign/Edit'
import Lottery from '../components/UserArea/Lottery'
import LotteryCreate from '../components/UserArea/Lottery/Create'
import LotteryEdit from '../components/UserArea/Lottery/Edit'
import Entry from '../components/UserArea/Entry'
import EntryEdit from '../components/UserArea/Entry/Edit'
import EntryChart from '../components/UserArea/Entry/Chart'
import Vote from '../components/UserArea/Vote'

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
      meta: { isUserArea: true },
      children: [
        { path: '', component: Dashboard },
        { path: 'campaigns', component: Campaign },
        { path: 'campaigns/create', component: CampaignCreate },
        { path: 'campaigns/:id', component: CampaignEdit },
        { path: 'campaigns/:campaignId/lotteries/', component: Lottery },
        { path: 'campaigns/:campaignId/lotteries/create', component: LotteryCreate },
        { path: 'campaigns/:campaignId/lotteries/:id', component: LotteryEdit },
        { path: 'campaigns/:campaignId/lotteries/:lotteryId/entries', component: Entry },
        { path: 'campaigns/:campaignId/lotteries/:lotteryId/entries/chart', component: EntryChart },
        { path: 'campaigns/:campaignId/lotteries/:lotteryId/entries/:id', component: EntryEdit },
        { path: 'votes', component: Vote }
      ]
    }

  ]
})

export default router
