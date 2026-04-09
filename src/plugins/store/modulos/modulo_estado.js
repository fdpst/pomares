const modulo_estado = {
    strict: false,

    state() {
        return {
            is_loading: false,

            auth: false,

            user: {
                name: '...',
                role: 0,
                email: '',
                id: '',
            },

            errors: {
                errors: {

                }
            },

            login_errors: {
                message: null
            }
        }
    },

    getters: {
        getloading: state => state.is_loading,

        geterrors: state => state.errors,

        getuser: state => state.user,

        getauth: state => state.auth,

        get_login_errors: state => state.login_errors
    },

    mutations: {
        is_loading: (state, status) => state.is_loading = status,

        is_errors: (state, errors) => state.errors = errors,

        user: (state, user) => state.user = user,

        auth: (state, status) => state.auth = status,

        set_login_errors: (state, errors) => state.login_errors = errors
    },

    actions: {
        isLoading: (context, status) => context.commit('is_loading', status),

        setErrors: (context, errors) => context.commit('is_errors', errors),

        setUser: (context, user) => context.commit('user', user),

        setAuth: (context, status) => context.commit('auth', status),

        setLoginErrors: (context, errors) => context.commit('set_login_errors', errors)
    }
}

export default modulo_estado;