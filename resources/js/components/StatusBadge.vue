<template>
    <span class="badge" :class="status">{{ label }}</span>
</template>

<script setup>
defineProps({
    status: { type: String, required: true },
    label: { type: String, required: true },
});
</script>

<style scoped>
.badge {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
}

.badge.online {
    background: #dcfce7;
    color: #166534;
}

.badge.offline {
    background: #fee2e2;
    color: #991b1b;
}

.badge.unknown {
    background: #eee;
    color: #555;
}
</style>
