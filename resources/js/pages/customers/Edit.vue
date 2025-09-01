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

const props = defineProps<{ customer: any }>();
const statusOptions = [
    { value: 1, label: 'Active' },
    { value: 0, label: 'Inactive' },
];
const form = reactive({
    code: props.customer.code,
    name: props.customer.name,
    email: props.customer.email,
    phone: props.customer.phone,
    address: props.customer.address,
    is_active: props.customer.is_active ? 1 : 0, // Convert boolean to number
});

function submit() {
    router.put(`/customers/${props.customer.id}`, form, {
        onSuccess: () => {
            if (window.showToast) {
                window.showToast('success', 'Customer updated successfully');
            }
        },
        onError: () => {
            if (window.showToast) {
                window.showToast('error', 'Failed to update customer');
            }
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <Heading title="Edit Customer" />
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <Input v-model="form.code" placeholder="Code" />
                <Input v-model="form.name" placeholder="Name" />
                <Input v-model="form.email" placeholder="Email" />
                <Input v-model="form.phone" placeholder="Phone" />
                <Input v-model="form.address" placeholder="Address" />
                <Select v-model="form.is_active" :options="statusOptions" placeholder="Select Status" />
            </div>
            <div class="flex gap-2">
                <Button @click="submit">Save</Button>
                <Button variant="secondary" @click="$inertia.visit('/customers')">Cancel</Button>
            </div>
        </div>
    </AppLayout>
</template>
