export default function useOrder() {
    const getAll = async () => {
        try {
            const response = await axios.get('/api/orders');
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    };

    const add = async (order) => {
        try {
            const response = await axios.post('/api/orders', order);
            return response;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const getOne = async (id) => {
        try {
            const response = await axios.get(`/api/orders/${id}`);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const update = async (id, order) => {
        try {
            const response = await axios.patch(`/api/orders/${id}`, order);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const deleteOne = async (id) => {
        try {
            const response = await axios.delete(`/api/orders/${id}`);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const addServiceToOrder = async (orderId, serviceId) => {
        try {
            const response = await axios.post(`/api/orders/${orderId}/services`, serviceId);
            return response;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const updateServiceToOrder = async (orderId, serviceId, total_quantity) => {
        try {
            const response = await axios.patch(`/api/orders/${orderId}/services/${serviceId}`, total_quantity);
            return response;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const deleteServiceToOrder = async (orderId, serviceId) => {
        try {
            const response = await axios.delete(`/api/orders/${orderId}/services/${serviceId}`);
            return response;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    return {
        getAll,
        add,
        getOne,
        update,
        deleteOne,
        addServiceToOrder,
        updateServiceToOrder,
        deleteServiceToOrder
    }
}