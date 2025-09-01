<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select } from '@/components/ui/select';

declare global {
    interface Window {
        showToast: (type: 'success' | 'error' | 'info', title?: string, message?: string) => void;
    }
}

const props = defineProps<{ warehouse: any }>();
const statusOptions = [
    { value: 1, label: 'Active' },
    { value: 0, label: 'Inactive' },
];
const form = reactive({
    code: props.warehouse.code,
    name: props.warehouse.name,
    address: props.warehouse.address,
    is_active: props.warehouse.is_active ? 1 : 0, // Convert boolean to number
});

function submit() {
    router.put(`/warehouses/${props.warehouse.id}`, form, {
        onSuccess: () => {
            if (window.showToast) {
                window.showToast('success', 'Warehouse updated successfully');
            }
        },
        onError: () => {
            if (window.showToast) {
                window.showToast('error', 'Failed to update warehouse');
            }
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <Heading title="Edit Warehouse" />
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <Input v-model="form.code" placeholder="Code" />
                <Input v-model="form.name" placeholder="Name" />
                <Input v-model="form.address" placeholder="Address" />
                <Select v-model="form.is_active" :options="statusOptions" placeholder="Select status" />
            </div>
            <div class="flex gap-2">
                <Button @click="submit">Update</Button>
                <Button variant="secondary" @click="$inertia.visit('/warehouses')">Cancel</Button>
            </div>
        </div>
    </AppLayout>
</template>
