<script lang="ts" setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import DeleteConfirmModal from '@/components/DeleteConfirmModal.vue';

declare global {
    interface Window {
        showToast: (type: 'success' | 'error' | 'info', title?: string, message?: string) => void;
    }
}

const props = defineProps<{ products: any; filters: { q?: string } }>();
const q = ref(props.filters?.q ?? '');
const showDeleteModal = ref(false);
const itemToDelete = ref<any>(null);

function search() {
    router.get('/products', { q: q.value }, { preserveState: true, replace: true });
}

function goCreate() {
    router.get('/products/create');
}

function edit(id: number) {
    router.get(`/products/${id}/edit`);
}

function showDeleteConfirm(item: any) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (itemToDelete.value) {
        router.delete(`/products/${itemToDelete.value.id}`, {
            onSuccess: () => {
                if (window.showToast) {
                    window.showToast('success', 'Product deleted successfully');
                }
            },
            onError: () => {
                if (window.showToast) {
                    window.showToast('error', 'Failed to delete product');
                }
            },
        });
    }
    showDeleteModal.value = false;
    itemToDelete.value = null;
}

function cancelDelete() {
    showDeleteModal.value = false;
    itemToDelete.value = null;
}
</script>

<template>
    <AppLayout>
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <Heading title="Products" />
                <Button @click="goCreate">New</Button>
            </div>
            <div class="flex gap-2">
                <Input v-model="q" placeholder="Search name or SKU" @keyup.enter="search" />
                <Button variant="secondary" @click="search">Search</Button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 pr-4">SKU</th>
                            <th class="py-2 pr-4">Name</th>
                            <th class="py-2 pr-4">Unit</th>
                            <th class="py-2 pr-4 text-right">Price</th>
                            <th class="py-2 pr-4 text-right">Stock</th>
                            <th class="py-2 pr-4">Active</th>
                            <th class="py-2 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in props.products.data" :key="p.id" class="border-b">
                            <td class="py-2 pr-4">{{ p.sku }}</td>
                            <td class="py-2 pr-4">{{ p.name }}</td>
                            <td class="py-2 pr-4">{{ p.unit }}</td>
                            <td class="py-2 pr-4 text-right">{{ Number(p.price).toFixed(2) }}</td>
                            <td class="py-2 pr-4 text-right">{{ Number(p.stock).toFixed(3) }}</td>
                            <td class="py-2 pr-4">{{ p.is_active ? 'Yes' : 'No' }}</td>
                            <td class="space-x-2 py-2 pr-4">
                                <Button size="sm" variant="secondary" @click="edit(p.id)">Edit</Button>
                                <Button size="sm" variant="destructive" @click="showDeleteConfirm(p)">Delete</Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <DeleteConfirmModal
            :item-name="itemToDelete?.name"
            :message="`Are you sure you want to delete the product '${itemToDelete?.name || ''}'?  This action cannot be undone.`"
            :show="showDeleteModal"
            title="Delete Product"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>
