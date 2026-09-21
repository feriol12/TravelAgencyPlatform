import { createRouter, createWebHistory } from 'vue-router'

export default createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('../layouts/PublicLayout.vue'),
      children: [
        {
          path: '',
          name: 'public',
          component: () => import('../views/public/PublicView.vue'),
        },
      ],
    },
    {
      path: '/client',
      component: () => import('../layouts/ClientLayout.vue'),
      children: [
        {
          path: '',
          name: 'client',
          component: () => import('../views/client/ClientView.vue'),
        },
      ],
    },
    {
      path: '/admin',
      component: () => import('../layouts/AdminLayout.vue'),
      children: [
        {
          path: '',
          name: 'admin',
          component: () => import('../views/admin/AdminView.vue'),
        },
      ],
    },
    {
      // Temporary smoke-test route for the Sanctum SPA authentication
      // foundation (card 01.0). Remove once a real login page exists.
      path: '/dev/auth-smoke',
      name: 'dev-auth-smoke',
      component: () => import('../views/dev/AuthSmokeView.vue'),
    },
  ],
})
