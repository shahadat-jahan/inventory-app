<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps<{ warehouses: any, filters: { q?: string } }>()
const q = ref(props.filters?.q ?? '')

function search() {
  router.get('/warehouses', { q: q.value }, { preserveState: true, replace: true })
}

function goCreate() { router.get('/warehouses/create') }
function editItem(id: number) { router.get(`/warehouses/${id}/edit`) }
function destroyItem(id: number) { if (confirm('Delete warehouse?')) router.delete(`/warehouses/${id}`) }
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading>Warehouses</Heading>
        <Button @click="goCreate">New</Button>
      </div>
      <div class="flex gap-2">
        <Input v-model="q" placeholder="Search name or code" @keyup.enter="search" />
        <Button variant="secondary" @click="search">Search</Button>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left border-b">
              <th class="py-2 pr-4">Code</th>
              <th class="py-2 pr-4">Name</th>
              <th class="py-2 pr-4">Address</th>
              <th class="py-2 pr-4">Active</th>
              <th class="py-2 pr-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="w in props.warehouses.data" :key="w.id" class="border-b">
              <td class="py-2 pr-4">{{ w.code }}</td>
              <td class="py-2 pr-4">{{ w.name }}</td>
              <td class="py-2 pr-4">{{ w.address }}</td>
              <td class="py-2 pr-4">{{ w.is_active ? 'Yes' : 'No' }}</td>
              <td class="py-2 pr-4 space-x-2">
                <Button size="sm" variant="secondary" @click="editItem(w.id)">Edit</Button>
                <Button size="sm" variant="destructive" @click="destroyItem(w.id)">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
