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

const form = reactive({
  code: '',
  name: '',
  email: '',
  phone: '',
  address: '',
  is_active: true,
})

function submit() {
  router.post('/suppliers', form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Supplier created successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to create supplier')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading title="New Supplier" />
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.code" placeholder="Code" />
        <Input v-model="form.name" placeholder="Name" />
        <Input v-model="form.email" placeholder="Email" />
        <Input v-model="form.phone" placeholder="Phone" />
        <Input v-model="form.address" placeholder="Address" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Create</Button>
        <Button variant="secondary" @click="$inertia.visit('/suppliers')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
 </template>


