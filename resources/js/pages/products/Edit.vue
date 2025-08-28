<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select } from '@/components/ui/select'; // Import your new Select component

declare global {
    interface Window {
        showToast: (type: 'success' | 'error' | 'info', title?: string, message?: string) => void;
    }
}

const props = defineProps<{ product: any }>();
const statusOptions = [
    { value: 1, label: 'Active' },
    { value: 0, label: 'Inactive' },
];
const form = reactive({
    sku: props.product.sku || '',
    name: props.product.name || '',
    description: props.product.description || '',
    unit: props.product.unit || '',
    price: props.product.price || 0,
    stock: props.product.stock || 0,
    is_active: props.product.is_active ? 1 : 0, // Convert boolean to number
});

// Debug: Check the values
console.log('Product data:', props.product);
console.log('Form is_active:', form.is_active, typeof form.is_active);
console.log('Status options:', statusOptions);

function submit() {
    router.put(`/products/${props.product.id}`, form, {
        onSuccess: () => {
            if (window.showToast) {
                window.showToast('success', 'Product updated successfully');
            }
        },
        onError: () => {
            if (window.showToast) {
                window.showToast('error', 'Failed to update product');
            }
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <Heading title="Edit Product" />
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <Input v-model="form.sku" placeholder="SKU" />
                <Input v-model="form.name" placeholder="Name" />
                <Input v-model="form.unit" placeholder="Unit" />
                <Input v-model.number="form.price" placeholder="Price" type="number" step="0.01" />
                <Input v-model.number="form.stock" placeholder="Stock" type="number" step="0.001" />
                <Input v-model.textarea="form.description" placeholder="Description" />
                <Select v-model="form.is_active" :options="statusOptions" placeholder="Select status" />
            </div>
            <div class="flex gap-2">
                <Button @click="submit">Save</Button>
                <Button variant="secondary" @click="$inertia.visit('/products')">Cancel</Button>
            </div>
        </div>
    </AppLayout>
</template>
