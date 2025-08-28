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

const props = defineProps<{ supplier: any }>()
const form = reactive({
  code: props.supplier.code,
  name: props.supplier.name,
  email: props.supplier.email,
  phone: props.supplier.phone,
  address: props.supplier.address,
  is_active: props.supplier.is_active,
})

function submit() {
  router.put(`/suppliers/${props.supplier.id}`, form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Supplier updated successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to update supplier')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading>Edit Supplier</Heading>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.code" placeholder="Code" />
        <Input v-model="form.name" placeholder="Name" />
        <Input v-model="form.email" placeholder="Email" />
        <Input v-model="form.phone" placeholder="Phone" />
        <Input v-model="form.address" placeholder="Address" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Save</Button>
        <Button variant="secondary" @click="$inertia.visit('/suppliers')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
 </template>


