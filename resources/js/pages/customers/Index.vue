<script setup lang="ts">
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

const props = defineProps<{ customers: any; filters: { q?: string } }>();
const q = ref(props.filters?.q ?? '');
const showDeleteModal = ref(false);
const itemToDelete = ref<any>(null);

function search() {
    router.get('/customers', { q: q.value }, { preserveState: true, replace: true });
}

function goCreate() {
    router.get('/customers/create');
}
function editItem(id: number) {
    router.get(`/customers/${id}/edit`);
}
function showDeleteConfirm(item: any) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (itemToDelete.value) {
        router.delete(`/customers/${itemToDelete.value.id}`, {
            onSuccess: () => {
                if (window.showToast) {
                    window.showToast('success', 'Customer deleted successfully');
                }
            },
            onError: () => {
                if (window.showToast) {
                    window.showToast('error', 'Failed to delete customer');
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
                <Heading title="Customers" />
                <Button @click="goCreate">New</Button>
            </div>
            <div class="flex gap-2">
                <Input v-model="q" placeholder="Search name or code" @keyup.enter="search" />
                <Button variant="secondary" @click="search">Search</Button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 pr-4">Code</th>
                            <th class="py-2 pr-4">Name</th>
                            <th class="py-2 pr-4">Email</th>
                            <th class="py-2 pr-4">Phone</th>
                            <th class="py-2 pr-4">Active</th>
                            <th class="py-2 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in props.customers.data" :key="c.id" class="border-b">
                            <td class="py-2 pr-4">{{ c.code }}</td>
                            <td class="py-2 pr-4">{{ c.name }}</td>
                            <td class="py-2 pr-4">{{ c.email }}</td>
                            <td class="py-2 pr-4">{{ c.phone }}</td>
                            <td class="py-2 pr-4">{{ c.is_active ? 'Yes' : 'No' }}</td>
                            <td class="space-x-2 py-2 pr-4">
                                <Button size="sm" variant="secondary" @click="editItem(c.id)">Edit</Button>
                                <Button size="sm" variant="destructive" @click="showDeleteConfirm(c)">Delete</Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <DeleteConfirmModal
            :item-name="itemToDelete ? itemToDelete.name : ''"
            :message="`Are you sure you want to delete this customer '${itemToDelete?.name || ''}'? This action cannot be undone.`"
            :show="showDeleteModal"
            title="Delete Customer"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>
