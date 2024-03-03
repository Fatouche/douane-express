export default function useService() {
    const getAll = async () => {
        try {
            const response = await axios.get('/api/services');
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    };

    const add = async (service) => {
        try {
            const response = await axios.post('/api/services', service);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const getOne = async (id) => {
        try {
            const response = await axios.get(`/api/services/${id}`);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const update = async (id, service) => {
        try {
            const response = await axios.patch(`/api/services/${id}`, service);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const deleteOne = async (id) => {
        try {
            const response = await axios.delete(`/api/services/${id}`);
            return response.data;
        } catch (error) {
            console.error(error);
            throw new Error(error.response.data.message);
        }
    }

    const uploadImage = async (id, image) => {
        try {
            const response = await axios.post(`/api/services/${id}/upload-image`, image);
            return response.data;
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
        uploadImage,
        deleteOne,
    }
}