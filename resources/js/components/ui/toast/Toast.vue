<script setup lang="ts">
import { computed } from 'vue'
import { CheckCircle, XCircle, Info, X } from 'lucide-vue-next'

interface Props {
  type?: 'success' | 'error' | 'info'
  title?: string
  message?: string
  show?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'info',
  show: false
})

const emit = defineEmits<{
  close: []
}>()

const icon = computed(() => {
  switch (props.type) {
    case 'success':
      return CheckCircle
    case 'error':
      return XCircle
    default:
      return Info
  }
})

const bgColor = computed(() => {
  switch (props.type) {
    case 'success':
      return 'bg-green-50 border-green-200'
    case 'error':
      return 'bg-red-50 border-red-200'
    default:
      return 'bg-blue-50 border-blue-200'
  }
})

const textColor = computed(() => {
  switch (props.type) {
    case 'success':
      return 'text-green-800'
    case 'error':
      return 'text-red-800'
    default:
      return 'text-blue-800'
  }
})

const iconColor = computed(() => {
  switch (props.type) {
    case 'success':
      return 'text-green-400'
    case 'error':
      return 'text-red-400'
    default:
      return 'text-blue-400'
  }
})

function close() {
  emit('close')
}
</script>

<template>
  <Transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      :class="[
        'pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg border shadow-lg ring-1 ring-black ring-opacity-5',
        bgColor
      ]"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <component
              :is="icon"
              :class="['h-6 w-6', iconColor]"
              aria-hidden="true"
            />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p v-if="title" :class="['text-sm font-medium', textColor]">
              {{ title }}
            </p>
            <p v-if="message" :class="['mt-1 text-sm', textColor]">
              {{ message }}
            </p>
          </div>
          <div class="ml-4 flex flex-shrink-0">
            <button
              type="button"
              :class="[
                'inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2',
                textColor,
                props.type === 'success' ? 'focus:ring-green-500' : '',
                props.type === 'error' ? 'focus:ring-red-500' : '',
                props.type === 'info' ? 'focus:ring-blue-500' : ''
              ]"
              @click="close"
            >
              <span class="sr-only">Close</span>
              <X class="h-5 w-5" aria-hidden="true" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>
