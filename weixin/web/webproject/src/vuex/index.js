import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


var state = {
    loading: false,
    usertype: 'docter',
    count: 111,
    user: {},
    token: true,
    redirectUrl: '',
    dotor_id: null,//访问时有特定的医生
    title: ''
};

const types = {
  LOGIN: 'login',
  LOGOUT: 'logout',
  TITLE:'title'
}

var actions = {//处理方法，判断
    increment: ({
        commit
    }) => {
        commit('increment')
    },
    decrement: ({commit}) => {//定义方法名
        commit('decrement')
    },
    casync: ({commit}) => {
        new Promise((resolve) => {setTimeout(function () {
            commit('increment')
        }, 1000)})
    }
};


var mutations = {//定义的方法，从actions接收
    increment(state) {
        state.count++;
    },
    decrement(state) {
        state.count--;
    },
    [types.LOGIN]: (state, data) => {
      localStorage.token = data;
      state.token = data;
    },
    [types.LOGOUT]: (state) => {
      localStorage.removeItem('token');
      state.token = null
    },
    [types.TITLE]: (state, data) => {
      state.title = data;
    }
};


var getters = {
    count(state) {
        return state.count;
    }
}

export default new Vuex.Store({
    getters,
    state,
    mutations,
    actions
})
