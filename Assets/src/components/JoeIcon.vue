<script setup>
import { computed } from 'vue'

//Icons
import 'ionicons-npm/css/ionicons.css'
import 'font-awesome/css/font-awesome.css'

const props = defineProps({
  icon: String
})

const iconClass = computed(() => {
  if (typeof props.icon == 'string' && props.icon.indexOf('ion') === 0) {
    return `ion ${props.icon}`
  } else {
    return `fa ${props.icon}`
  }
})
</script>

<template>
  <div class="joe-icon">
    <i :class="iconClass" />
  </div>
</template>

<style lang="less">
.joe-icon {
  display: inline-block;

  i {
    padding: 8px;
    font-size: 24px;
    line-height: 100%;
  }
}
</style>
