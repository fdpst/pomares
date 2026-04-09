import axios from "axios";
import { ref } from "vue";

const provinces = ref([]);
const countries = ref([]);

export function useProvincias() {
    const getProvincias = () => {
        axios
            .get(`/api/get-provincias`)
            .then((res) => {
                provinces.value = res.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            });
    };

    const getPaises = function () {
        axios
            .get(`/api/get-paises`)
            .then((res) => {
                countries.value = res.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            });
    };

    return {
        provinces,
        countries,
        getProvincias,
        getPaises,
    };
}
