import axios from "axios";

const series = ref([]);
const form_series = ref({
    serie: "",
    id: null,
});

export function useSeries() {
    const findAllSeries = async () => {
        const res = await axios.get(`/api/invoice-series`);
        series.value = res.data.map((serie) => ({
            ...serie,
            label: serie.serie,
            value: serie.id,
        }));
    };
    const submitSeries = async (serie) => {
        try {
            if (!serie?.id) {
                await axios.post(`/api/invoice-series`, serie);
                $toast.sucs("Serie creada con éxito.");
            } else {
                await axios.put(`/api/invoice-series/${serie.id}`, serie);
                $toast.sucs("Serie actualizada con éxito.");
            }
            findAllSeries();
        } catch (error) {
            console.log(error);
            $toast.err("Error al crear/actualizar la serie");
        }
    };
    const onEditSerie = (item) => {
        console.log("item", item);
        form_series.value = item;
    };
    const onResetSerieForm = () => {
        form_series.value = {
            id: null,
            serie: "",
        };
    };
    const onDeleteSerie = async (id) => {
        try {
            await axios.delete(`/api/invoice-series/${id}`);
            $toast.sucs("Serie eliminada exitosamente");
            findAllSeries();
        } catch (error) {
            $toast.err("Error al eliminar la serie");
        }
    };

    return {
        series,
        form_series,
        findAllSeries,
        submitSeries,
        onEditSerie,
        onResetSerieForm,
        onDeleteSerie,
    };
}
