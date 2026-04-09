import axios from "axios";

export class CustomerSearch {
    static getInstance() {
        return new this();
    }

    /**
     * @param {string} search
     */
    handleSearch(search) {
        return this.debounce(this.search, 1000);
    }

    /**
     * @param {string} search
     */
    async search(search) {
        return (
            await axios.get(
                `/api/get-clientes?search=${
                    search || ""
                }`
            )
        ).data;
    }

    /**
     * @param {function} callback
     * @param {number} delay
     * @returns {Promise<any>}
     */
    debounce(callback, delay) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                callback.apply(context, args);
            }, delay);
        };
    }
}
