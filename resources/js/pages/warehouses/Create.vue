<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

declare global {
    interface Window {
        showToast: (type: 'success' | 'error' | 'info', title?: string, message?: string) => void;
    }
}

const form = reactive({
    code: '',
    name: '',
    address: '',
    is_active: true,
});

function submit() {
    router.post('/warehouses', form, {
        onSuccess: () => {
            if (window.showToast) {
                window.showToast('success', 'Warehouse created successfully');
            }
        },
        onError: () => {
            if (window.showToast) {
                window.showToast('error', 'Failed to create warehouse');
            }
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <Heading title="New Warehouse" />
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <Input v-model="form.code" placeholder="Code" />
                <Input v-model="form.name" placeholder="Name" />
                <Input v-model="form.address" placeholder="Address" />
            </div>
            <div class="flex gap-2">
                <Button @click="submit">Create</Button>
                <Button variant="secondary" @click="$inertia.visit('/warehouses')">Cancel</Button>
            </div>
        </div>
    </AppLayout>
</template>
