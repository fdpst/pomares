import axios from 'axios';
import { store } from './../plugins/store/index';

export default function setup_interceptors() 
{
  axios.interceptors.request.use(function(config) 
  {
    store.dispatch('isLoading', true);
    store.dispatch('setErrors', 
    {
      errors:{}
    })
    return config;
  }, function(err) 
  {
    return Promise.reject(err);
  });

  axios.interceptors.response.use(function(config) 
  {
    store.dispatch('isLoading', false);
    return config;
  }, function(err) 
  {
    console.log(err)

    if(err.response.status == 422)
    {
      store.dispatch('setErrors', err.response.data)
    }
    store.dispatch('isLoading', false);
    return Promise.reject(err);
  });
}
