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

const props = defineProps<{ warehouse: any }>()
const form = reactive({
  code: props.warehouse.code,
  name: props.warehouse.name,
  address: props.warehouse.address,
  is_active: props.warehouse.is_active,
})

function submit() {
  router.put(`/warehouses/${props.warehouse.id}`, form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Warehouse updated successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to update warehouse')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading title="Edit Warehouse" />
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.code" placeholder="Code" />
        <Input v-model="form.name" placeholder="Name" />
        <Input v-model="form.address" placeholder="Address" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Save</Button>
        <Button variant="secondary" @click="$inertia.visit('/warehouses')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
</template>
