<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps<{ workOrder: any, products: any[] }>()
const form = reactive({
  number: props.workOrder.number,
  product_id: props.workOrder.product_id,
  quantity: props.workOrder.quantity,
  status: props.workOrder.status,
  start_date: props.workOrder.start_date,
  completion_date: props.workOrder.completion_date,
  notes: props.workOrder.notes,
})

function submit() {
  router.put(`/work-orders/${props.workOrder.id}`, form)
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading>Edit Work Order</Heading>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.number" placeholder="Work Order Number" />
        <select v-model="form.product_id" class="border rounded-md px-3 py-2">
          <option value="">Select Product</option>
          <option v-for="product in props.products" :key="product.id" :value="product.id">
            {{ product.name }}
          </option>
        </select>
        <Input v-model.number="form.quantity" placeholder="Quantity" type="number" step="0.001" />
        <select v-model="form.status" class="border rounded-md px-3 py-2">
          <option value="planned">Planned</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <Input v-model="form.start_date" placeholder="Start Date" type="date" />
        <Input v-model="form.completion_date" placeholder="Completion Date" type="date" />
        <Input v-model="form.notes" placeholder="Notes" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Save</Button>
        <Button variant="secondary" @click="$inertia.visit('/work-orders')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
</template>
