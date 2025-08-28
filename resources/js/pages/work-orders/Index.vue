<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps<{ workOrders: any, filters: { q?: string } }>()
const q = ref(props.filters?.q ?? '')

function search() {
  router.get('/work-orders', { q: q.value }, { preserveState: true, replace: true })
}

function goCreate() { router.get('/work-orders/create') }
function editItem(id: number) { router.get(`/work-orders/${id}/edit`) }
function destroyItem(id: number) { if (confirm('Delete work order?')) router.delete(`/work-orders/${id}`) }
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading>Work Orders</Heading>
        <Button @click="goCreate">New</Button>
      </div>
      <div class="flex gap-2">
        <Input v-model="q" placeholder="Search work order number" @keyup.enter="search" />
        <Button variant="secondary" @click="search">Search</Button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left border-b">
              <th class="py-2 pr-4">Number</th>
              <th class="py-2 pr-4">Product</th>
              <th class="py-2 pr-4">Quantity</th>
              <th class="py-2 pr-4">Status</th>
              <th class="py-2 pr-4">Start Date</th>
              <th class="py-2 pr-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="wo in props.workOrders.data" :key="wo.id" class="border-b">
              <td class="py-2 pr-4">{{ wo.number }}</td>
              <td class="py-2 pr-4">{{ wo.product?.name }}</td>
              <td class="py-2 pr-4">{{ Number(wo.quantity).toFixed(3) }}</td>
              <td class="py-2 pr-4">{{ wo.status }}</td>
              <td class="py-2 pr-4">{{ wo.start_date }}</td>
              <td class="py-2 pr-4 space-x-2">
                <Button size="sm" variant="secondary" @click="editItem(wo.id)">Edit</Button>
                <Button size="sm" variant="destructive" @click="destroyItem(wo.id)">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
