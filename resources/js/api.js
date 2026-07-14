import axios from 'axios';

const client = axios.create({
    baseURL: '/api',
    headers: { Accept: 'application/json' },
});

export default {
    list: () => client.get('/proxies').then((response) => response.data.data),
    create: (data) => client.post('/proxies', data).then((response) => response.data.data),
    update: (id, data) => client.put(`/proxies/${id}`, data).then((response) => response.data.data),
    remove: (id) => client.delete(`/proxies/${id}`),
    check: (id) => client.post(`/proxies/${id}/check`).then((response) => response.data.data),
    checkAll: () => client.post('/proxies/check-all').then((response) => response.data.data),
};
