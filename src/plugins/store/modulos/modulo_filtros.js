export default {
    strict: false,

    state() {
        return {
            
        }
    },

    getters: {
        getFilters: state=>state,
    },

    mutations: {
        set_filters: (state, filter) => state[filter.name] = filter.data
    },

    actions: {
        setFilter: (context, filter) => context.commit('set_filters', filter),

    }
}
