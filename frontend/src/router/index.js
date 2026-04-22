import { route } from 'quasar/wrappers'
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import routes from './routes'

export default route(function ({ store }) {
  const router = createRouter({
    history: createWebHistory(process.env.VUE_ROUTER_BASE),
    routes
  })

  router.beforeEach((to, from, next) => {
    const authStore = useAuthStore(store)

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
      return next({ name: 'login' })
    }

    if (to.meta.guest && authStore.isAuthenticated) {
      return next({ name: 'home' })
    }

    if (to.meta.roles && authStore.usuario) {
      const userRole = authStore.usuario.role
      if (!to.meta.roles.includes(userRole)) {
        return next({ name: 'home' })
      }
    }

    next()
  })

  return router
})
