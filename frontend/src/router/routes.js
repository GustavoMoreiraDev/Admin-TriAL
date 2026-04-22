const routes = [
  {
    path: '/',
    redirect: '/app/dashboard'
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('pages/auth/LoginPage.vue'),
    meta: { guest: true }
  },
  {
    path: '/termos',
    name: 'termos',
    component: () => import('pages/auth/TermosPage.vue'),
  },
  {
    path: '/suporte',
    name: 'suporte',
    component: () => import('pages/auth/SuportePage.vue'),
  },

  // App autenticado 
  {
    path: '/app',
    component: () => import('layouts/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: '',          redirect: '/app/dashboard' },
      { path: 'dashboard', name: 'home',     component: () => import('pages/dashboard/DashboardPage.vue') },
      { path: 'users',     name: 'users',    component: () => import('pages/dashboard/UsersPage.vue'),    meta: { roles: ['administrador', 'editor'] } },
      { path: 'profile',   name: 'profile',  component: () => import('pages/dashboard/ProfilePage.vue')   },
      { path: 'settings',  name: 'settings', component: () => import('pages/dashboard/SettingsPage.vue')  },
      { path: 'search',    name: 'search',   component: () => import('pages/dashboard/SearchPage.vue')    },
    ]
  },

  {
    path: '/:catchAll(.*)*',
    redirect: '/login'
  }
]

export default routes
