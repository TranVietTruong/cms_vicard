import Login from './views/login'
import Error404 from './views/error404'
import Home from './views/home'

import store from './store'


const isAuthenticated = (to, from, next) => {
  if(store.getters.authenticated) {
    return next()
  } else {
    return next({name:'login'})
  }
}

const checkAuth = (to, from, next) => {
  if(store.getters.authenticated) {
    return next({name:'home'})
  } else {
    return next()
  }
}

export const routes = [
  {
    path: '/',
    name: 'login',
    component: Login,
    beforeEnter: checkAuth
  },
  {
    path: '/home',
    name: 'home',
    component: Home,
    beforeEnter: isAuthenticated
  },
  {
    path: '*',
    name: '404',
    component: Error404
  }
];
