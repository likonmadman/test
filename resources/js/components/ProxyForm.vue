<template>
    <div class="overlay">
        <div class="modal">
            <h2>{{ proxy ? 'Изменить прокси' : 'Добавить прокси' }}</h2>

            <form @submit.prevent="submit">
                <label>
                    Протокол
                    <select v-model="form.protocol">
                        <option value="http">HTTP</option>
                        <option value="https">HTTPS</option>
                        <option value="socks4">SOCKS4</option>
                        <option value="socks5">SOCKS5</option>
                    </select>
                </label>

                <label>
                    Хост
                    <input v-model="form.host" type="text">
                </label>
                <span v-if="errors.host" class="error">{{ errors.host[0] }}</span>

                <label>
                    Порт
                    <input v-model.number="form.port" type="number">
                </label>
                <span v-if="errors.port" class="error">{{ errors.port[0] }}</span>

                <label>
                    Логин
                    <input v-model="form.username" type="text">
                </label>
                <span v-if="errors.username" class="error">{{ errors.username[0] }}</span>

                <label>
                    Пароль
                    <input v-model="form.password" type="password" :placeholder="proxy ? 'оставьте пустым, чтобы не менять' : ''">
                </label>
                <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>

                <span v-if="formError" class="error">{{ formError }}</span>

                <div class="actions">
                    <button type="button" @click="$emit('close')">Отмена</button>
                    <button type="submit" class="primary" :disabled="saving">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import api from '../api';

const props = defineProps({
    proxy: { type: Object, default: null },
});

const emit = defineEmits(['close', 'saved']);

const form = reactive({
    protocol: props.proxy?.protocol ?? 'http',
    host: props.proxy?.host ?? '',
    port: props.proxy?.port ?? '',
    username: props.proxy?.username ?? '',
    password: '',
});

const errors = ref({});
const formError = ref('');
const saving = ref(false);

async function submit() {
    saving.value = true;
    errors.value = {};
    formError.value = '';

    try {
        if (props.proxy) {
            await api.update(props.proxy.id, form);
        } else {
            await api.create(form);
        }
        emit('saved');
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors;
        } else {
            formError.value = 'Не удалось сохранить, попробуйте ещё раз';
        }
    } finally {
        saving.value = false;
    }
}
</script>

<style scoped>
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal {
    background: #fff;
    padding: 24px;
    width: 340px;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

h2 {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 600;
}

label {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
    color: #374151;
}

input,
select {
    box-sizing: border-box;
    display: block;
    width: 100%;
    margin-top: 4px;
}

.error {
    display: block;
    margin: -6px 0 10px;
    color: #b91c1c;
    font-size: 13px;
}

.actions {
    margin-top: 16px;
    text-align: right;
}

.actions button {
    margin-left: 8px;
}
</style>
