export const date_mixin = {
  methods: {
    format_date_filter(created_at) {
      return moment(created_at).format('DD-MM-Y')
    }
  }
}
