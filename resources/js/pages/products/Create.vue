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
  sku: '',
  name: '',
  description: '',
  unit: 'pcs',
  price: 0,
  stock: 0,
  is_active: true,
})

function submit() {
  router.post('/products', form, {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('success', 'Product created successfully')
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('error', 'Failed to create product')
      }
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <Heading title="New Product" />
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Input v-model="form.sku" placeholder="SKU" />
        <Input v-model="form.name" placeholder="Name" />
        <Input v-model="form.unit" placeholder="Unit" />
        <Input v-model.number="form.price" placeholder="Price" type="number" step="0.01" />
        <Input v-model.number="form.stock" placeholder="Stock" type="number" step="0.001" />
        <Input v-model="form.description" placeholder="Description" />
      </div>
      <div class="flex gap-2">
        <Button @click="submit">Create</Button>
        <Button variant="secondary" @click="$inertia.visit('/products')">Cancel</Button>
      </div>
    </div>
  </AppLayout>
</template>


