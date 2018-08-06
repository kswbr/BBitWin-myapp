import Router from 'vue-router'
import Form from '../components/Form.vue'

const router = new Router({
  mode: 'hash',
  routes: [
    {
      path: '/',
      name: 'Form',
      component: Form
    }
  ]
})

export default router
