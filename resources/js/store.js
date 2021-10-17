import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    token: null,
    user: null,
    step: '1'
  },
  getters: {
    authenticated(state) {
      return state.token && state.user;
    },
    user(state) {
      return state.user;
    },
    getStep(state) {
      return state.step;
    }
  },
  mutations: {
    setToken(state, token) {
      state.token = token;
    },
    setUser(state, user) {
      state.user = user
    },
    setStep(state, step) {
      state.step = step
    }
  },
  actions: {
    async attempt({dispatch, commit}, credentials) {
      await axios.post('auth/login', credentials).then((res) => {
        const accessToken = res.data.data;
        return dispatch('getUser', accessToken)
      })
    },
    async getUser({commit, state}, token) {
      if(token) {
        commit('setToken', token);
      }
      if(!state.token) {
        return
      }

      await axios.get('auth/me', {
        headers: {
          'Authorization': 'Bearer '+token
        }
      }).then((res) => {
        const user = res.data.data
        commit('setUser', user)
      })
      .catch(error => {
        commit('setToken', null)
        commit('setUser', null)
      });
    },
    async register({dispatch}, data) {
      return await axios.post('auth/register', data);
    },
    async logout({commit}) {
      await axios.post('auth/logout', { clearCacheEntry: true }).then((res) => {
        commit('setToken', null);
        commit('setUser', null);
      })
      .catch(() => {
        commit('setToken', null);
        commit('setUser', null);
      });
    }
  }
})
store.subscribe((mutation) => {
  if(mutation.type === 'setToken') {
    if(mutation.payload) {
      window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + mutation.payload
      localStorage.setItem('accessToken', mutation.payload)
    }
    else {
      window.axios.defaults.headers.common['Authorization'] = null
      localStorage.removeItem('accessToken')
    }
  }
})

export default store;
