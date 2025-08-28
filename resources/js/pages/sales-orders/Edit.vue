<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

declare global {
  interface Window {
    showToast: (type: 'success' | 'error' | 'info', title?: string, message?: string) => void
  }
}

const props = defineProps<{ salesOrder: any, customers: any[] }>()
const form = reactive({
  number: props.salesOrder.number,
  customer_id: props.salesOrder.customer_id,
  order_date: props.salesOrder.order_date,
  expected_date: props.salesOrder.expected_date,
  status: props.salesOrder.status,
  total_amount: props.salesOrder.total_amount,
  notes: props.salesOrder.notes,
})

function submit() {
  router.put(`/sales-orders/${props.salesOrder.id}`, form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Sales order updated successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to update sales order')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading title="Edit Sales Order" />
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.number" placeholder="Sales Order Number" />
        <select v-model="form.customer_id" class="border rounded-md px-3 py-2">
          <option value="">Select Customer</option>
          <option v-for="customer in props.customers" :key="customer.id" :value="customer.id">
            {{ customer.name }}
          </option>
        </select>
        <Input v-model="form.order_date" placeholder="Order Date" type="date" />
        <Input v-model="form.expected_date" placeholder="Expected Date" type="date" />
        <select v-model="form.status" class="border rounded-md px-3 py-2">
          <option value="draft">Draft</option>
          <option value="confirmed">Confirmed</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <Input v-model.number="form.total_amount" placeholder="Total Amount" type="number" step="0.01" />
        <Input v-model="form.notes" placeholder="Notes" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Save</Button>
        <Button variant="secondary" @click="$inertia.visit('/sales-orders')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
</template>
