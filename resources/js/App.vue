<template>
    <div class="page">
        <div class="header">
            <h1>Proxy manager</h1>
            <div>
                <button @click="refreshAll" :disabled="checkingAll">
                    {{ checkingAll ? 'Проверяю...' : 'Обновить все' }}
                </button>
                <button class="primary" @click="openCreate">Добавить</button>
            </div>
        </div>

        <p v-if="error" class="error">{{ error }}</p>

        <p v-if="loading">Загрузка...</p>

        <table v-else>
            <thead>
                <tr>
                    <th>Протокол</th>
                    <th>Адрес</th>
                    <th>Логин</th>
                    <th>Статус</th>
                    <th>Задержка</th>
                    <th>Проверен</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="proxy in proxies" :key="proxy.id">
                    <td>{{ proxy.protocol }}</td>
                    <td>{{ proxy.host }}:{{ proxy.port }}</td>
                    <td>{{ proxy.username ?? '—' }}</td>
                    <td><StatusBadge :status="proxy.status" :label="proxy.status_label" /></td>
                    <td>{{ proxy.latency_ms ? `${proxy.latency_ms} мс` : '—' }}</td>
                    <td>{{ formatDate(proxy.last_checked_at) }}</td>
                    <td class="actions">
                        <button @click="check(proxy)" :disabled="isChecking(proxy)">
                            {{ isChecking(proxy) ? 'Проверяю...' : 'Проверить' }}
                        </button>
                        <button @click="openEdit(proxy)">Изменить</button>
                        <button class="danger" @click="remove(proxy)">Удалить</button>
                    </td>
                </tr>
                <tr v-if="!proxies.length">
                    <td colspan="7">Прокси пока нет</td>
                </tr>
            </tbody>
        </table>

        <ProxyForm v-if="showForm" :proxy="editing" @close="showForm = false" @saved="onSaved" />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import api from './api';
import StatusBadge from './components/StatusBadge.vue';
import ProxyForm from './components/ProxyForm.vue';

const proxies = ref([]);
const loading = ref(true);
const showForm = ref(false);
const editing = ref(null);
const checking = ref(new Set());
const checkingAll = ref(false);
const error = ref('');

let timer;

async function load() {
    try {
        proxies.value = await api.list();
        error.value = '';
    } catch {
        error.value = 'Не удалось загрузить список';
    } finally {
        loading.value = false;
    }
}

function openCreate() {
    editing.value = null;
    showForm.value = true;
}

function openEdit(proxy) {
    editing.value = proxy;
    showForm.value = true;
}

function onSaved() {
    showForm.value = false;
    load();
}

function isChecking(proxy) {
    return checkingAll.value || checking.value.has(proxy.id);
}

async function check(proxy) {
    checking.value.add(proxy.id);

    try {
        const updated = await api.check(proxy.id);
        proxies.value = proxies.value.map((p) => (p.id === updated.id ? updated : p));
        error.value = '';
    } catch {
        error.value = 'Не удалось проверить прокси';
    } finally {
        checking.value.delete(proxy.id);
    }
}

async function refreshAll() {
    checkingAll.value = true;

    try {
        proxies.value = await api.checkAll();
        error.value = '';
    } catch {
        error.value = 'Не удалось проверить прокси';
    } finally {
        checkingAll.value = false;
    }
}

async function remove(proxy) {
    if (!confirm(`Удалить прокси ${proxy.host}:${proxy.port}?`)) {
        return;
    }

    try {
        await api.remove(proxy.id);
        load();
    } catch {
        error.value = 'Не удалось удалить прокси';
    }
}

function formatDate(value) {
    return value ? new Date(value).toLocaleString('ru-RU') : '—';
}

onMounted(() => {
    load();
    timer = setInterval(load, 30000);
});

onUnmounted(() => clearInterval(timer));
</script>

<style scoped>
.page {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h1 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

th,
td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

th {
    background: #f9fafb;
    font-size: 13px;
    color: #6b7280;
}

tr:last-child td {
    border-bottom: none;
}

tbody tr:hover {
    background: #f9fafb;
}

button {
    margin-right: 4px;
}

.actions {
    white-space: nowrap;
}

.error {
    color: #b91c1c;
}
</style>
