<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps<{ purchaseOrders: any, filters: { q?: string } }>()
const q = ref(props.filters?.q ?? '')

function search() {
  router.get('/purchase-orders', { q: q.value }, { preserveState: true, replace: true })
}

function goCreate() { router.get('/purchase-orders/create') }
function editItem(id: number) { router.get(`/purchase-orders/${id}/edit`) }
function destroyItem(id: number) { if (confirm('Delete purchase order?')) router.delete(`/purchase-orders/${id}`) }
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading>Purchase Orders</Heading>
        <Button @click="goCreate">New</Button>
      </div>
      <div class="flex gap-2">
        <Input v-model="q" placeholder="Search purchase order number" @keyup.enter="search" />
        <Button variant="secondary" @click="search">Search</Button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left border-b">
              <th class="py-2 pr-4">Number</th>
              <th class="py-2 pr-4">Supplier</th>
              <th class="py-2 pr-4">Order Date</th>
              <th class="py-2 pr-4">Status</th>
              <th class="py-2 pr-4">Total Amount</th>
              <th class="py-2 pr-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="po in props.purchaseOrders.data" :key="po.id" class="border-b">
              <td class="py-2 pr-4">{{ po.number }}</td>
              <td class="py-2 pr-4">{{ po.supplier?.name }}</td>
              <td class="py-2 pr-4">{{ po.order_date }}</td>
              <td class="py-2 pr-4">{{ po.status }}</td>
              <td class="py-2 pr-4">{{ Number(po.total_amount).toFixed(2) }}</td>
              <td class="py-2 pr-4 space-x-2">
                <Button size="sm" variant="secondary" @click="editItem(po.id)">Edit</Button>
                <Button size="sm" variant="destructive" @click="destroyItem(po.id)">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
