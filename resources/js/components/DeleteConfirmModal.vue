<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { AlertTriangle } from 'lucide-vue-next'

interface Props {
  show: boolean
  title?: string
  message?: string
  itemName?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Confirm Delete',
  message: 'Are you sure you want to delete this item? This action cannot be undone.',
  itemName: 'this item'
})

const emit = defineEmits<{
  confirm: []
  cancel: []
}>()

function confirm() {
  emit('confirm')
}

function cancel() {
  emit('cancel')
}
</script>

<template>
  <Dialog :open="show" @update:open="cancel">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle class="flex items-center gap-2">
          <AlertTriangle class="h-5 w-5 text-red-500" />
          {{ title }}
        </DialogTitle>
        <DialogDescription>
          {{ message }}
          <span v-if="itemName" class="font-semibold text-gray-900">{{ itemName }}</span>?
        </DialogDescription>
      </DialogHeader>
      <DialogFooter class="gap-2">
        <Button variant="outline" @click="cancel">
          Cancel
        </Button>
        <Button variant="destructive" @click="confirm">
          Delete
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
