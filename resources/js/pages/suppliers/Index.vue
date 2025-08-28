<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

const props = defineProps<{ suppliers: any, filters: { q?: string } }>()
const q = ref(props.filters?.q ?? '')

function search() {
  router.get('/suppliers', { q: q.value }, { preserveState: true, replace: true })
}

function goCreate() { router.get('/suppliers/create') }
function editItem(id: number) { router.get(`/suppliers/${id}/edit`) }
function destroyItem(id: number) { if (confirm('Delete supplier?')) router.delete(`/suppliers/${id}`) }
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading>Suppliers</Heading>
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
              <th class="py-2 pr-4">Email</th>
              <th class="py-2 pr-4">Phone</th>
              <th class="py-2 pr-4">Active</th>
              <th class="py-2 pr-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in props.suppliers.data" :key="s.id" class="border-b">
              <td class="py-2 pr-4">{{ s.code }}</td>
              <td class="py-2 pr-4">{{ s.name }}</td>
              <td class="py-2 pr-4">{{ s.email }}</td>
              <td class="py-2 pr-4">{{ s.phone }}</td>
              <td class="py-2 pr-4">{{ s.is_active ? 'Yes' : 'No' }}</td>
              <td class="py-2 pr-4 space-x-2">
                <Button size="sm" variant="secondary" @click="editItem(s.id)">Edit</Button>
                <Button size="sm" variant="destructive" @click="destroyItem(s.id)">Delete</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
 </template>


