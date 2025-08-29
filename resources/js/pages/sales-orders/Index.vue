<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import DeleteConfirmModal from '@/components/DeleteConfirmModal.vue';

const props = defineProps<{ salesOrders: any; filters: { q?: string } }>();
const q = ref(props.filters?.q ?? '');
const showDeleteModal = ref(false);
const itemToDelete = ref<any>(null);

function search() {
    router.get('/sales-orders', { q: q.value }, { preserveState: true, replace: true });
}
function goCreate() {
    router.get('/sales-orders/create');
}
function editItem(id: number) {
    router.get(`/sales-orders/${id}/edit`);
}
function showDeleteConfirm(item: any) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (itemToDelete.value) {
        router.delete(`/sales-orders/${itemToDelete.value.id}`, {
            onSuccess: () => {
                if (window.showToast) {
                    window.showToast('success', 'Sales order deleted successfully');
                }
            },
            onError: () => {
                if (window.showToast) {
                    window.showToast('error', 'Failed to delete Sales order');
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
                <Heading title="Sales Orders" />
                <Button @click="goCreate">New</Button>
            </div>
            <div class="flex gap-2">
                <Input v-model="q" placeholder="Search sales order number" @keyup.enter="search" />
                <Button variant="secondary" @click="search">Search</Button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 pr-4">Number</th>
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Order Date</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Total Amount</th>
                            <th class="py-2 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="so in props.salesOrders.data" :key="so.id" class="border-b">
                            <td class="py-2 pr-4">{{ so.number }}</td>
                            <td class="py-2 pr-4">{{ so.customer?.name }}</td>
                            <td class="py-2 pr-4">{{ so.order_date }}</td>
                            <td class="py-2 pr-4">{{ so.status }}</td>
                            <td class="py-2 pr-4">{{ Number(so.total_amount).toFixed(2) }}</td>
                            <td class="space-x-2 py-2 pr-4">
                                <Button size="sm" variant="secondary" @click="editItem(so.id)">Edit</Button>
                                <Button size="sm" variant="destructive" @click="showDeleteConfirm(so)">Delete</Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <DeleteConfirmModal
            :item-name="itemToDelete?.number"
            :message="`Are you sure you want to delete the sales order '${itemToDelete?.number || ''}'?  This action cannot be undone.`"
            :show="showDeleteModal"
            title="Delete sales order"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>
