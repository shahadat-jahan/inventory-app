<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Toast from './Toast.vue'

interface ToastMessage {
  id: string
  type: 'success' | 'error' | 'info'
  title?: string
  message?: string
  show: boolean
}

const toasts = ref<ToastMessage[]>([])

function showToast(type: 'success' | 'error' | 'info', title?: string, message?: string) {
  const id = Date.now().toString()
  const toast: ToastMessage = {
    id,
    type,
    title,
    message,
    show: true
  }
  
  toasts.value.push(toast)
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    removeToast(id)
  }, 5000)
}

function removeToast(id: string) {
  const toast = toasts.value.find(t => t.id === id)
  if (toast) {
    toast.show = false
    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, 300)
  }
}

// Expose methods globally
onMounted(() => {
  window.showToast = showToast
})

defineExpose({
  showToast,
  removeToast
})
</script>

<template>
  <div
    aria-live="assertive"
    class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50"
  >
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <Toast
        v-for="toast in toasts"
        :key="toast.id"
        :type="toast.type"
        :title="toast.title"
        :message="toast.message"
        :show="toast.show"
        @close="removeToast(toast.id)"
      />
    </div>
  </div>
</template>
