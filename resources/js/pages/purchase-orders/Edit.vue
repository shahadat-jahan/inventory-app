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

const props = defineProps<{ purchaseOrder: any, suppliers: any[] }>()
const form = reactive({
  number: props.purchaseOrder.number,
  supplier_id: props.purchaseOrder.supplier_id,
  order_date: props.purchaseOrder.order_date,
  expected_date: props.purchaseOrder.expected_date,
  status: props.purchaseOrder.status,
  total_amount: props.purchaseOrder.total_amount,
  notes: props.purchaseOrder.notes,
})

function submit() {
  router.put(`/purchase-orders/${props.purchaseOrder.id}`, form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Purchase order updated successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to update purchase order')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading title="Edit Purchase Order" />
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.number" placeholder="Purchase Order Number" />
        <select v-model="form.supplier_id" class="border rounded-md px-3 py-2">
          <option value="">Select Supplier</option>
          <option v-for="supplier in props.suppliers" :key="supplier.id" :value="supplier.id">
            {{ supplier.name }}
          </option>
        </select>
        <Input v-model="form.order_date" placeholder="Order Date" type="date" />
        <Input v-model="form.expected_date" placeholder="Expected Date" type="date" />
        <select v-model="form.status" class="border rounded-md px-3 py-2">
          <option value="draft">Draft</option>
          <option value="ordered">Ordered</option>
          <option value="received">Received</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <Input v-model.number="form.total_amount" placeholder="Total Amount" type="number" step="0.01" />
        <Input v-model="form.notes" placeholder="Notes" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Save</Button>
        <Button variant="secondary" @click="$inertia.visit('/purchase-orders')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
</template>
